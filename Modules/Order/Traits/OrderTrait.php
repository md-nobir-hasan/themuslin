<?php

namespace Modules\Order\Traits;

use App\Http\Services\Commission;
use Crypt;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Hash;
use Modules\Inventory\Http\Services\Frontend\FrontendInventoryService;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderAddress;
use Modules\Order\Entities\OrderPaymentMeta;
use Modules\Order\Entities\OrderTrack;
use Modules\Order\Entities\SubOrderCommission;
use Modules\Order\Services\ApiCartServices;
use Modules\Order\Services\CheckoutCouponService;
use Modules\Order\Services\OrderShippingChargeService;
use Modules\Order\Services\OrderTaxService;
use Modules\Order\Services\PaymentGatewayService;
use Modules\TaxModule\Entities\TaxClassOption;
use Modules\TaxModule\Services\CalculateTaxBasedOnCustomerAddress;
use Modules\User\Entities\User;
use Modules\Wallet\Http\Services\WalletService;
use stdClass;
use Str;
use Throwable;

trait OrderTrait{
    use StoreOrderTrait, OrderMailTrait;

    protected static function cartInstanceName(): string
    {
        return "default";
    }

    public static function groupByColumn(): string
    {
        return "options.vendor_id";
    }


    /**
    "": {
     *      product_id: 12,
     *      image: 12,
     *      price: 120,
     *      options: {
     *          categories: 2.
     *          subCategories: 1,
     *          childCategories: []
     *      }
     * },{
     *      product_id: 12,
     *      image: 12,
     *      price: 120,
     *      options: {
     *          categories: 2.
     *          subCategories: 1,
     *          childCategories: []
     *      }
     * }
     */

    /**
     * @return bool
     */
    protected static function isVendorMailable(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    protected static function isAdminMailable(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    protected static function isUserMailable(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    protected static function isMailSendWithQueue(): bool
    {
        return true;
    }

    protected static function groupByCartContent($cartContent){
        return $cartContent->groupBy(self::groupByColumn());
    }

    private static function cartContent($request = null, $type = null){
        if($type == 'pos'){
            return Cart::instance(self::cartInstanceName())->content();
        }
        return (is_null($type) || is_null($request)) ? Cart::instance(self::cartInstanceName())->content() : ApiCartServices::prepareRequestDataForCartContent($request);
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    protected static function orderProcess($request, $type = null): array
    {
        // get all cart items and make a group by with vendor_id
        $cartContent = self::cartContent($request, $type);
        $groupedData = self::groupByCartContent($cartContent);

        // Initialize OrderShippingChargeService for
        // declare a temporary variable for storing totalAmount
        $total_amount = 0;
        $shippingTaxClass = new stdClass();

        // if request comes from pos then go forward with totalShippingCharge 0 if request is not coming from pos then store shipping address and totalShippingCharge from database
        if ($type != 'pos'){
            $shippingTaxClass = TaxClassOption::where("class_id", get_static_option("shipping_tax_class"))->sum("rate");
            $shippingCost = OrderShippingChargeService::getShippingCharge($request["shipping_cost"]);
            $shippingCostTemp = 0;

            // this loop will care of all available vendor shipping charges
            foreach($shippingCost["vendor"] ?? [] as $s_cost){
                $shippingCostTemp += calculatePrice($s_cost->cost, $shippingTaxClass,"shipping");
            }

            // this line of code will take care of admin shipping charge if you do not have then plus with 0
            $shippingCostTemp += calculatePrice($shippingCost["admin"]?->cost ?? 0, $shippingTaxClass, "shipping") ?? 0;
            // sum total shipping cost
            $totalShippingCharge = $shippingCostTemp;
        }else{
            $totalShippingCharge = 0;
        }

        // create order
        $order = self::storeOrder($request,$type);

        // if request comes not from pos, then store OrderShippingAddress and stores it in a variable
        if ($type != 'pos') {
            $orderAddress = self::storeOrderShippingAddress($request, $order->id);
        }else{
            $orderAddress = self::storeOrderShippingAddress($request->validated(), $order->id);
        }

        // create order track status default ordered
        self::storeOrderTrack($order->id,"ordered", auth('web')?->user()?->id ?? 0, 'users');

        // now get taxPercentage from a database based on customer billing address if request not comes from pos
        $taxPercentage = $type == 'pos' ? get_static_option("default_shopping_tax") :
            (int) OrderTaxService::taxAmount($orderAddress->country_id, $orderAddress->state_id ?? null, $orderAddress->city ?? null);

        $tax = CalculateTaxBasedOnCustomerAddress::init();
        $uniqueProductIds = $cartContent->pluck("id")->unique()->toArray();

        $price_type = "";
        $taxProducts = collect([]);
        if(CalculateTaxBasedOnCustomerAddress::is_eligible()){
            $taxProducts = $tax
                ->productIds($uniqueProductIds)
                ->customerAddress(
                    ($type == "pos" ? get_static_option("pos_tax_country") : $request['country_id']),
                    ($type == "pos" ? get_static_option("pos_tax_state") : $request['state_id'] ?? null),
                    ($type == "pos" ? get_static_option("pos_tax_city") : $request['city'] ?? null)
                )->generate();

            $price_type = "billing_address";
        }elseif(CalculateTaxBasedOnCustomerAddress::is_eligible_inclusive()){
            $price_type = "inclusive_price";
        }else{
            $price_type = "zone_wise_tax";
        }

        $totalTaxAmount = 0;

        // now we need to run a loop for managing admin and vendor order and those data should be stored into this two methods prepareOrderForVendor , prepareOrderForAdmin
        foreach($groupedData as $key => $data){
            $subOrderTotal = 0;
            // get shipping charge amount from request
            $vendor_id = $key;
            // sub order shipping cost store on a temporary variable
            $subOrderShippingCost = 0;

            if($key == ""){
                // This is admin
                $vendor_id = null;
                // :
                if ($type != 'pos'){
                    $subOrderShippingCost = $shippingCost['admin']->cost ?? 0;
                }
            }else{
                if ($type != 'pos') {
                    $subOrderShippingCost = $shippingCost['vendor']
                        ->where("vendor_id", $key)->first()->cost ?? 0;
                }
            }

            // declare a temporary variable for orderItem
            $orderItem = [];
            $orderTotalAmount = 0;
            $subOrderTaxAmount = 0;

            foreach($data as $cart){
                $total_amount += $cart->price * $cart->qty;
                $orderTotalAmount += $cart->price * $cart->qty;
                $subOrderTotal  += $cart->price * $cart->qty;

                // check a data type here
                if(is_object($cart->options)){
                    $cart->options = (array) $cart->options;
                }

                $cart->options['vendor_id'] = $key;

                if($price_type == "billing_address" && $taxProducts->isNotEmpty()){
                    $taxAmount = $taxProducts->find($cart->id) ?? (object) [];
                    $taxAmount = calculateOrderedPrice($cart->price,$taxAmount->tax_options_sum_rate ?? 0, $price_type);
                }elseif ($price_type == "inclusive_price"){
                    $taxAmount = calculateOrderedPrice($cart->price,$cart->options['tax_options_sum_rate'] ?? 0, $price_type);
                }else{
                    $taxAmount = 0;
                }

                $optionVendor = $cart->options["variant_id"] ?? null;

                $orderItem[] = [
                    "sub_order_id" => 0,
                    "order_id" => $order->id,
                    "product_id" => (int) $cart->id,
                    "variant_id" => (($optionVendor ?? null) != null) ?
                        ($optionVendor == "admin" ? null : $optionVendor) : null,
                    "quantity" => (int) $cart->qty,
                    "price" => $cart->price,
                    "sale_price" => $cart->options['regular_price'] ?? 0,
                    "tax_amount" => $taxAmount,
                    "tax_type" => $price_type,
                ];

                if($price_type == "inclusive_price"){
                    $subOrderTaxAmount = 0;
                }elseif ($price_type == "billing_address"){
                    $subOrderTaxAmount += $taxAmount * (int) $cart->qty;
                }

                $totalTaxAmount += $taxAmount * (int) $cart->qty;
            }

            if($price_type != "billing_address" && $price_type != "inclusive_price"){
                $subOrderTaxAmount = $orderTotalAmount * $taxPercentage / 100;
            }

            $vendor_id = $vendor_id == "admin" ? null : $vendor_id;

            // get a commission of this suborder by sending two parameter one is vendor id another one is sub-total
            $subOrder = self::storeSubOrder($order->id,$vendor_id,$subOrderTotal,$subOrderShippingCost,$subOrderTaxAmount,$price_type, $orderAddress->id ?? null);
            // store suborder commissions
            self::createSubOrderCommission($subOrderTotal,$subOrder->id, $vendor_id);

            for($i = 0, $length = count($orderItem ?? []);$i < $length; $i++){
                $orderItem[$i]["sub_order_id"] = $subOrder->id;
            }

            self::storeSubOrderItem($orderItem);
        }
        
        $coupon_amount = CheckoutCouponService::calculateCoupon((object) $request, $total_amount, $cartContent, 'DISCOUNT');
        $orderSubTotal = ($total_amount - $coupon_amount);

        if($type == "zone_wise_tax"){
            $totalTaxAmount = ($orderSubTotal * $taxPercentage) / 100;
        }

        $finalAmount = $orderSubTotal + $totalTaxAmount + $totalShippingCharge;
        // now store OrderPaymentMeta
        $orderPaymentMeta = self::storePaymentMeta($order->id,$total_amount,$coupon_amount,$totalShippingCharge,$totalTaxAmount,$finalAmount);

        // check wallet module if exist then move forward for next action
        // check for selecting wallet as a payment gateway
        if(($request['payment_gateway'] == 'Wallet' && auth('web')->check()) && moduleExists("Wallet")){
            // before update database checks user wallet amount if it is a getter then finalAmount or equal then user will be eligible for next action
            WalletService::updateUserWallet(auth('web')->user()?->id,$finalAmount,false,'balance',$order->id,checkBalance: true);
        }

        // now update product inventory
        FrontendInventoryService::updateInventory($order->id);

        return $orderPaymentMeta ? [
            "success" => true,
            "type" => "success",
            "order_id" => $order->id,
            "total_amount" => $finalAmount,
            "tested" => encrypt($order->payment_status),
            "secrete_key" => Hash::make($order->transaction_id),
            "invoice_number" => $order->invoice_number,
            "created_at" => $order->created_at->format("Y-m-d H:i:s")
        ] : [
            "success" => false,"type" => "danger",
            "order_id" => null
        ];
    }

    private static function createSubOrderCommission($sub_total, $sub_order_id,$vendor_id){
        $commission = Commission::get($sub_total,$vendor_id);

        return SubOrderCommission::create($commission + [
            "sub_order_id" => $sub_order_id,
            "vendor_id" => $vendor_id
        ]);
    }

    public static function sendOrder($request){

    }

    /**
     * @param $request
     * @param null $type
     * @return mixed
     * @throws Throwable
     */
    public static function testOrder($request, $type = null)
    {
//        try {
//            \DB::beginTransaction();

            $order_process = self::orderProcess($request,$type);

            if($type != 'pos'){
                if($request["payment_gateway"] == 'cash_on_delivery'){
                    // do something for cash on delivery
                    // now send email using this method,
                    // and this method will take care all the process
                    self::sendOrderMail(order_process: $order_process, request: $request);
                    WalletService::updateWallet($order_process["order_id"]);

                    Cart::instance(self::cartInstanceName())->destroy();
                }elseif($request["payment_gateway"] == 'manual_payment'){
                    // do something for manual payment
                    // now send email using this method,
                    // and this method will take care all the process
                    self::sendOrderMail(order_process: $order_process, request: $request);
                    WalletService::updateWallet($order_process["order_id"]);

                    Cart::instance(self::cartInstanceName())->destroy();
                }elseif($request["payment_gateway"] == 'Wallet'){
                    // do something for manual payment
                    // now send email using this method,
                    //and this method will take care all the process
                    self::sendOrderMail(order_process: $order_process, request: $request);
                    WalletService::updateWallet($order_process["order_id"]);

                    Cart::instance(self::cartInstanceName())->destroy();
                }else{
                    \DB::commit();
                    Cart::instance(self::cartInstanceName())->destroy();

                    return (new PaymentGatewayService)->payment_with_gateway($request['payment_gateway'] , $request, $order_process["order_id"] , round($order_process['total_amount'] , 0));
                }
            }else{
                // do something for cash on delivery
                // now send email using this method, and this method will take care all the process
                if(!empty($request->selected_customer ?? '') && $request->send_email == 'on'){
                    self::sendOrderMail(order_process: $order_process, request: $request, type: 'pos');
                }

                WalletService::updateWallet($order_process["order_id"]);
                //todo:: add order amount from pending balance to main balance
                WalletService::completeOrderAmount($order_process["order_id"]);
                //todo:: add wallet history that mean's transaction history

                //todo:: now update into database
                Order::where("id", $order_process["order_id"])->update([
                    "order_status" => 'complete',
                    "payment_status" => 'complete',
                ]);

                OrderTrack::insert([
                    ['order_id' => $order_process["order_id"], 'updated_by' => auth("admin")->id(),'table' => 'admin', 'name' => 'picked_by_courier'],
                    ['order_id' => $order_process["order_id"], 'updated_by' => auth("admin")->id(),'table' => 'admin', 'name' => 'on_the_way'],
                    ['order_id' => $order_process["order_id"], 'updated_by' => auth("admin")->id(),'table' => 'admin', 'name' => 'ready_for_pickup'],
                    ['order_id' => $order_process["order_id"], 'updated_by' => auth("admin")->id(),'table' => 'admin', 'name' => 'delivered'],
                ]);

                \DB::commit();
                Cart::instance(self::cartInstanceName())->destroy();

                $selectedCustomer = User::with("userCountry","userState","userCity")->find($request->selected_customer ?? 0);
                if ($order_process["order_id"]) {
                    return response()->json([
                        "msg" => __("Purchase complete"),
                        "type" => "success",
                        "order_details" => [
                            "site_info" => [
                                "name" => get_static_option("site_title"),
                                "email" => get_static_option("site_global_email"),
                                "website" => env("app_url")
                            ],
                            "customer" => [
                                "name" => $request->selected_customer ? $selectedCustomer->name : __("Walk in customer"),
                                "phone" =>  $request->selected_customer ? $selectedCustomer->phone : "No Number",
                                "email" => $request->selected_customer ? $selectedCustomer->email : null,
                                "country" => $request->selected_customer ? $selectedCustomer?->userCountry?->name ?? "" : null,
                                "state" => $request->selected_customer ? $selectedCustomer?->userState?->name ?? "" : null,
                                "city" => $request->selected_customer ? $selectedCustomer?->userCity?->name ?? "" : null,
                                "address" => $request->selected_customer ? $selectedCustomer?->address ?? "" : null
                            ],
                            "invoice_number" => $order_process['invoice_number'],
                            "date" => $order_process["created_at"],
                            "order_id" => $order_process['order_id']
                        ]
                    ]);
                } else {
                    return response()->json([
                        "msg" => __("Purchase failed"),
                        "type" => "error",
                        "order_details" => []
                    ]);
                }

            }

//            \DB::commit();
//            return redirect()->route('frontend.order.payment.success', $order_process['order_id']);
//
//        }catch(Exception $e){
//            Cart::instance(self::cartInstanceName())->destroy();
//            $response = [
//                "type" => "danger",
//                "msg" => $e->getMessage()
//            ];
//
//            if($type == 'pos'){
//                return response()->json($response);
//            }
//
//            return back()->with($response);
//        }
    }

    /**
     * @param $request
     * @return mixed
     * @throws Throwable
     */
    public static function apiOrder($request): mixed
    {
//        try {
            // now check order process status if truer than send email if mailable is true
            $order = self::orderProcess($request, "api");

            if($order["success"]){
                OrderAddress::where("order_id", $order["order_id"])->first();
            }

            if($request["payment_gateway"] == 'cash_on_delivery') {
                // do something for cash on delivery
                // now send email using this method, and this method will take care all the process
                self::sendOrderMail(order_process: $order, request: $request);

                WalletService::updateWallet($order["order_id"]);
            }elseif($request["payment_gateway"] == 'paytm'){
                return (new PaymentGatewayService)->payment_with_gateway($request['payment_gateway'] , $request, $order["order_id"] , round($order['total_amount'] , 0));
            }elseif($request["payment_gateway"] == 'manual_payment'){
                if ($request['transaction_attachment'] ?? false) {
                    $image = request()->file('transaction_attachment');
                    $image_extension = $image->extension();
                    $image_name_with_ext = $image->getClientOriginalName();

                    $image_name = pathinfo($image_name_with_ext, PATHINFO_FILENAME);
                    $image_name = strtolower(Str::slug($image_name));
                    $image_db = $image_name . time() . '.' . $image_extension;

                    $path = 'assets/uploads/payment_attachments/';
                    $image->move($path, $image_db);

                    //  :: now update payment metas table with this column
                    OrderPaymentMeta::where("order_id", $order["order_id"])->update([
                        "payment_attachments" => $image_db
                    ]);
                }

                // do something for manual payment
                // now send email using this method, and this method will take care all the process
                self::sendOrderMail(order_process: $order, request: $request);
                WalletService::updateWallet($order["order_id"]);
            }

            return $order + ["hash" => \Hash::make(json_encode($order)), "hash-two" => Crypt::encryptString(json_encode($order))];
//        } catch (Exception $e) {
//            return $e->getMessage();
//        }
    }

    protected static function prepareOrderForVendor($vendor_id,$order_id, $total_amount, $shipping_cost, $tax_amount, $order_address_id) : array
    {
        // for preparing cart data we should add those in a array key order_id	vendor_id	total_amount	shipping_cost	tax_amount	order_address_id
        // and after this we should prepareOrderItems like  sub_order_id	order_id	product_id	variant_id	quantity	price	sale_price

        return [
            "order_id" => $order_id,
            "vendor_id" => $vendor_id,
            "total_amount" => $total_amount,
            "shipping_cost" => $shipping_cost,
            "tax_amount" => $tax_amount,
            "order_address_id" => $order_address_id
        ];
    }
    protected static function prepareOrderForAdmin($sub_order_id,$order_id,$product_id,$variant_id,$quantity,$price,$sale_price) : array
    {
        return [
            "sub_order_id" => $sub_order_id,
            "order_id" => $order_id,
            "product_id" => $product_id,
            "variant_id" => $variant_id,
            "quantity" => $quantity,
            "price" => $price,
            "sale_price" => $sale_price,
        ];
    }
}