<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CustomServiceByDc;
use App\Http\Requests\Frontend\SubmitCheckoutRequest;
use App\Http\Services\Commission;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use Modules\Inventory\Http\Services\Frontend\FrontendInventoryService;
use Modules\Order\Emails\OrderAdminMail;
use Modules\Order\Emails\OrderUserMail;
use Modules\Order\Emails\OrderVendorMail;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderAddress;
use Modules\Order\Entities\OrderPaymentMeta;
use Modules\Order\Entities\OrderTrack;
use Modules\Order\Entities\SubOrder;
use Modules\Order\Entities\SubOrderCommission;
use Modules\Order\Entities\SubOrderItem;
use Modules\Order\Services\ApiCartServices;
use Modules\Order\Services\CheckoutCouponService;
use Modules\Order\Services\OrderService;
use Modules\Order\Services\OrderShippingChargeService;
use Modules\Order\Services\OrderTaxService;
use Modules\Order\Services\PaymentGatewayService;
use Modules\TaxModule\Entities\TaxClassOption;
use Modules\Order\Services\DhlshipmentService;
use Modules\TaxModule\Services\CalculateTaxBasedOnCustomerAddress;
use Modules\User\Entities\User;
use Modules\Wallet\Http\Services\WalletService;
use stdClass;
use Hash;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;

class DcastaliaOrderController extends Controller
{
    private string $totalAmount;
    private string $couponAmount;
    private string $subTotal;
    private string $taxAmount;
    private string $shippingCost;
    private static $self;
    private static $vendor_ids;

    public function __construct()
    {
        $this->couponAmount = 0;
    }

    public function checkout(SubmitCheckoutRequest $request)
    {
        $data = $request->validated();
        
        return self::placeOrder($data);
    }

    protected static function cartInstanceName(): string
    {
        return "default";
    }

    public static function placeOrder($request, $type = null)
    {
        $order_process = self::orderProcess($request, $type);

        if (!$order_process['success']) {

            $msg = 'Product(s) removed due to being out of stock.';

            return redirect(route($order_process['route'] == 'checkout' ? 'frontend.cart-checkout' : 'homepage'))->with('error', $msg);
        }

        if ($request["payment_gateway"] == 'cash_on_delivery') {

            self::sendOrderMail(order_process: $order_process, request: $request);

            Cart::instance(self::cartInstanceName())->destroy();
            $orderId = $order_process["order_id"];
            DhlshipmentService::cratedhlshipment($orderId);
            session()->flash('success', 'Your order has been placed successfully');

            return redirect(route('my-profile') . '#orders');

        } else {

            \DB::commit();

            $orderId = $order_process["order_id"];
            $orderData = [];
            $payment_details = Order::with('address', 'paymentMeta')->find($orderId);
            if($payment_details){


                $countryData = Country::where('id', $payment_details->address->country_id)
                    ->first();
                $StateData = State::where('country_id', $payment_details->address->country_id)
                    ->where('id', $payment_details->address->state_id)
                    ->first();

                $orderData['order_id']= $payment_details->address->order_id;
                $orderData['name']= $payment_details->address->name;
                $orderData['email']= $payment_details->address->email;
                $orderData['phone']= $payment_details->address->phone;
                $orderData['country']= $countryData->name;
                $orderData['state']= $StateData->name;
                $orderData['address']= $payment_details->address->address;
                $orderData['zipcode']= $payment_details->address->zipcode;
                $orderData['amount']= $payment_details->paymentMeta->total_amount;
            }

            Cart::instance(self::cartInstanceName())->destroy();

            self::onlinePaymentInitiate($orderData);

            self::sendOrderMail(order_process: $order_process, request: $request);
            DhlshipmentService::cratedhlshipment($orderId);
            return redirect(route('my-profile') . '#orders');

        }
    }

    private static function cartContent($request = null, $type = null)
    {   
        if ($type == 'pos') {
            return Cart::instance(self::cartInstanceName())->content();
        }
        return (is_null($type) || is_null($request)) ? Cart::instance(self::cartInstanceName())->content() : ApiCartServices::prepareRequestDataForCartContent($request);
    }

    protected static function groupByCartContent($cartContent)
    {
        return $cartContent->groupBy(self::groupByColumn());
    }

    public static function groupByColumn(): string
    {
        return "options.vendor_id";
    }


    private static function storeOrder($request, $type = null): mixed
    {
        $self = (new self);
        $invoiceNumber = Order::select('id', 'invoice_number')->orderBy("id", "desc")->first()?->invoice_number;

        $userId = auth()->id();


        return Order::create([
            "coupon" => $request["coupon"] ?? "",
            "coupon_amount" => $self->couponAmount,
            "payment_track" => Str::random(10) . Str::random(10),
            "payment_gateway" => $request['payment_gateway'],
            "transaction_id" => Str::random(10) . Str::random(10),
            "order_status" => $request['payment_gateway'] == 'Wallet' ? 'complete' : 'pending',
            "payment_status" => $request['payment_gateway'] == 'Wallet' ? 'complete' : 'pending',
            "invoice_number" => $invoiceNumber ? $invoiceNumber + 1 : 00001,
            "user_id" => $userId,
            "type" => $type,
            "note" => $request["note"] ?? null,
            "selected_customer" => $request["selected_customer"] ?? $userId,
            "shipping_id" => $request["shipping_id"] ?? $userId
        ]);
    }

    protected static function orderProcess($request, $type = null): array
    {
        // get all cart items and make a group by with vendor_id
        $cartContent = self::cartContent($request, $type);
        $groupedData = self::groupByCartContent($cartContent);

        $check_stock = self::checkProductInventory($cartContent);

        if(!$check_stock) {

            $cartdata = Cart::instance('default')->content();

            return ["success" => false, "type" => "danger", "route" => count($cartdata) > 0 ? 'checkout' : 'home'];
        }
        
        // Initialize OrderShippingChargeService for
        // declare a temporary variable for storing totalAmount
        $total_amount = 0;
        $shippingTaxClass = new stdClass();

        // if request comes from pos then go forward with totalShippingCharge 0 if request is not coming from pos then store shipping address and totalShippingCharge from database
        if ($type != 'pos') {
            if(empty($request["dhlshipingcost"])){
                $shippingTaxClass = TaxClassOption::where("class_id", get_static_option("shipping_tax_class"))->sum("rate");
                $shippingCost = OrderShippingChargeService::getShippingCharge($request["shipping_cost"]);
                $shippingCostTemp = 0;
    
                // this loop will care of all available vendor shipping charges
                foreach ($shippingCost["vendor"] ?? [] as $s_cost) {
                    $shippingCostTemp += calculatePrice($s_cost->cost, $shippingTaxClass, "shipping");
                }
    
                // this line of code will take care of admin shipping charge if you do not have then plus with 0
                $shippingCostTemp += calculatePrice($shippingCost["admin"]?->cost ?? 0, $shippingTaxClass, "shipping") ?? 0;
                // sum total shipping cost
                $totalShippingCharge = $shippingCostTemp;
            }else{
                $totalShippingCharge = $request["dhlshipingcost"];
            }
            
        } else {
            $totalShippingCharge = 0;
        }

        // create order
        $order = self::storeOrder($request, $type);

        // if request comes not from pos, then store OrderShippingAddress and stores it in a variable
        if ($type != 'pos') {
            $orderAddress = self::storeOrderShippingAddress($request, $order->id);
        } else {
            $orderAddress = self::storeOrderShippingAddress($request->validated(), $order->id);
        }

        // create order track status default ordered
        self::storeOrderTrack($order->id, "ordered", auth('web')?->user()?->id ?? 0, 'users');

        // now get taxPercentage from a database based on customer billing address if request not comes from pos
        $taxPercentage = $type == 0;

        $tax = CalculateTaxBasedOnCustomerAddress::init();
        $uniqueProductIds = $cartContent->pluck("id")->unique()->toArray();

        $price_type = "";
        $taxProducts = collect([]);
        if (CalculateTaxBasedOnCustomerAddress::is_eligible()) {
            $taxProducts = $tax
                ->productIds($uniqueProductIds)
                ->customerAddress(
                    ($type == "pos" ? get_static_option("pos_tax_country") : $request['country_id']),
                    ($type == "pos" ? get_static_option("pos_tax_state") : $request['state_id'] ?? null),
                    ($type == "pos" ? get_static_option("pos_tax_city") : $request['city'] ?? null)
                )->generate();

            $price_type = "billing_address";
        } elseif (CalculateTaxBasedOnCustomerAddress::is_eligible_inclusive()) {
            $price_type = "inclusive_price";
        } else {
            $price_type = "zone_wise_tax";
        }

        $totalTaxAmount = 0;

        // now we need to run a loop for managing admin and vendor order and those data should be stored into this two methods prepareOrderForVendor , prepareOrderForAdmin
        foreach ($groupedData as $key => $data) {
            $subOrderTotal = 0;
            // get shipping charge amount from request
            $vendor_id = $key;
            // sub order shipping cost store on a temporary variable
            $subOrderShippingCost = 0;

            if ($key == "") {
                // This is admin
                $vendor_id = null;
                // :
                if ($type != 'pos') {
                    $subOrderShippingCost = $shippingCost['admin']->cost ?? 0;
                }
            } else {
                if ($type != 'pos') {
                    $subOrderShippingCost = $shippingCost['vendor']
                        ->where("vendor_id", $key)->first()->cost ?? 0;
                }
            }

            // declare a temporary variable for orderItem
            $orderItem = [];
            $orderTotalAmount = 0;
            $subOrderTaxAmount = 0;

            foreach ($data as $cart) {
                $total_amount += $cart->price * $cart->qty;
                $orderTotalAmount += $cart->price * $cart->qty;
                $subOrderTotal += $cart->price * $cart->qty;

                // check a data type here
                if (is_object($cart->options)) {
                    $cart->options = (array)$cart->options;
                }

                $cart->options['vendor_id'] = $key;

                if ($price_type == "billing_address" && $taxProducts->isNotEmpty()) {
                    $taxAmount = $taxProducts->find($cart->id) ?? (object)[];
                    $taxAmount = calculateOrderedPrice($cart->price, $taxAmount->tax_options_sum_rate ?? 0, $price_type);
                } elseif ($price_type == "inclusive_price") {
                    $taxAmount = calculateOrderedPrice($cart->price, $cart->options['tax_options_sum_rate'] ?? 0, $price_type);
                } else {
                    $taxAmount = 0;
                }

                $optionVendor = $cart->options["variant_id"] ?? null;

                foreach ($cart->options as $index => $optionData) {
                    if (isset($optionData['variant_id'])) {
                        $optionVendor = $optionData['variant_id'];
                    }
                }


                $orderItem[] = [
                    "sub_order_id" => 0,
                    "order_id" => $order->id,
                    "product_id" => (int)$cart->id,
                    "variant_id" => (($optionVendor ?? null) != null) ?
                        ($optionVendor == "admin" ? null : $optionVendor) : null,
                    "quantity" => (int)$cart->qty,
                    "price" => $cart->price,
                    "sale_price" => $cart->options['regular_price'] ?? 0,
                    "tax_amount" => $taxAmount,
                    "tax_type" => $price_type,
                ];

                if ($price_type == "inclusive_price") {
                    $subOrderTaxAmount = 0;
                } elseif ($price_type == "billing_address") {
                    $subOrderTaxAmount += $taxAmount * (int)$cart->qty;
                }

                $totalTaxAmount += $taxAmount * (int)$cart->qty;
            }

            if ($price_type != "billing_address" && $price_type != "inclusive_price") {
                $subOrderTaxAmount = $orderTotalAmount * $taxPercentage / 100;
            }

            $vendor_id = $vendor_id == "admin" ? null : $vendor_id;

            // get a commission of this suborder by sending two parameter one is vendor id another one is sub-total
            $subOrder = self::storeSubOrder($order->id, $vendor_id, $subOrderTotal, $subOrderShippingCost, $subOrderTaxAmount, $price_type, $orderAddress->id ?? null);
            // store suborder commissions
            self::createSubOrderCommission($subOrderTotal, $subOrder->id, $vendor_id);

            for ($i = 0, $length = count($orderItem ?? []); $i < $length; $i++) {
                $orderItem[$i]["sub_order_id"] = $subOrder->id;
            }

            self::storeSubOrderItem($orderItem);
        }

        $all_cart_items = Cart::instance("default")->content();
        $products = Product::with("category", "subCategory", "childCategory")->whereIn('id', $all_cart_items?->pluck("id")?->toArray())->get();

        $coupon_amount = CheckoutCouponService::calculateCoupon((object)$request, $total_amount,  $products, 'DISCOUNT');

        $orderSubTotal = ($total_amount - $coupon_amount);

        if ($type == "zone_wise_tax") {
            $totalTaxAmount = ($orderSubTotal * $taxPercentage) / 100;
        }

        $finalAmount = $orderSubTotal + $totalTaxAmount + $totalShippingCharge;
        // now store OrderPaymentMeta
        $orderPaymentMeta = self::storePaymentMeta($order->id, $total_amount, $coupon_amount, $totalShippingCharge, $totalTaxAmount, $finalAmount);

        // check wallet module if exist then move forward for next action
        // check for selecting wallet as a payment gateway
        // if (($request['payment_gateway'] == 'Wallet' && auth('web')->check()) && moduleExists("Wallet")) {
        //     // before update database checks user wallet amount if it is a getter then finalAmount or equal then user will be eligible for next action
        //     WalletService::updateUserWallet(auth('web')->user()?->id, $finalAmount, false, 'balance', $order->id, checkBalance: true);
        // }

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
            "success" => false, "type" => "danger",
            "order_id" => null
        ];
    }


    private static function checkProductInventory($products)
    {
        $response = true;

        foreach ($products as $key => $product) {

            if ($product->options->variant_id !== null) {

                $variants = ProductInventoryDetail::where(['product_id' => $product->id, 'id' => $product->options->variant_id])->first();

                if (empty($variants) || (!empty($variants) && $variants->stock_count < $product->qty)) {

                    Cart::instance('default')->remove($product->rowId);
                    $response = false;
                }
            }
            else {

                $product_inventory = ProductInventory::where('product_id', $product->id)->first();

                if ($product_inventory && (int)$product_inventory->stock_count < (int)$product->qty){
                    Cart::instance('default')->remove($product->rowId);
                    $response = false;
                }
            }
        }

        return $response;
            
    }

    public static function sendOrderMail($order_process, $request, $type = null): mixed
    {
        try {

            // first need to fetch order address of this order
            if ($type != 'pos') {
                $orderAddress = OrderAddress::where("order_id", $order_process['order_id'])->first()->toArray();
            } else {
                if (!empty($request->selected_customer)) {
                    $orderAddress = User::find($request->selected_customer)->toArray();
                }
            }
            
            // check isUserMailable is true then send mail for ordered user
            if (self::isUserMailable() && !empty($orderAddress["email"])) {

                \Mail::to($orderAddress["email"], get_static_option('site_title'))->send(new OrderUserMail(["order_id" => $order_process['order_id'], 'request' => $orderAddress, 'type' => $type]));
            }

            // check isAdminMailable is true then send mail for admin
            if (self::isAdminMailable() && $type != 'pos') {
                \Mail::to(get_static_option("site_global_email"))->send(new OrderAdminMail(["order_id" => $order_process['order_id'], 'request' => $orderAddress, 'type' => $type]));
            }

        } catch (\Exception $e) {
            return $e;
        }

        return true;
    }


    private static function storePaymentMeta($order_id, $sub_total, $coupon_amount, $shipping_cost, $tax_amount, $total_amount)
    {
        return OrderPaymentMeta::create([
            "order_id" => $order_id,
            "sub_total" => $sub_total,
            "coupon_amount" => $coupon_amount,
            "shipping_cost" => $shipping_cost,
            "tax_amount" => $tax_amount,
            "total_amount" => $total_amount
        ]);
    }

    private static function storeSubOrderItem($data): mixed
    {
        return SubOrderItem::insert($data);
    }

    protected static function isVendorMailable(): bool
    {
        return true;
    }


    protected static function isAdminMailable(): bool
    {
        return true;
    }

    protected static function isUserMailable(): bool
    {
        return true;
    }

    private static function createSubOrderCommission($sub_total, $sub_order_id, $vendor_id)
    {
        $commission = Commission::get($sub_total, $vendor_id);

        return SubOrderCommission::create($commission + [
                "sub_order_id" => $sub_order_id,
                "vendor_id" => $vendor_id
            ]);
    }

    private static function storeOrderShippingAddress($data, $order_id)
    {
        return OrderAddress::create(["order_id" => $order_id] + $data);
    }

    private static function storeSubOrder($order_id, $vendor_id, $total_amount, $shipping_cost, $tax_amount, $type, $order_address_id): mixed
    {
        return SubOrder::create(array(
            "order_id" => $order_id,
            "vendor_id" => $vendor_id,
            "total_amount" => $total_amount,
            "shipping_cost" => $shipping_cost,
            "tax_amount" => $tax_amount,
            "tax_type" => $type,
            "order_address_id" => $order_address_id,
            "order_number" => rand(000000000000, 999999999999),
            "payment_status" => 'pending',
            "order_status" => 'pending',
        ));
    }

    public static function storeOrderTrack($order_id, $name, $updated_by, $table): mixed
    {
        return OrderTrack::create([
            "order_id" => $order_id,
            "name" => $name, // This name is for orderTrack name as like order sent , order confirm
            "updated_by" => $updated_by,
            "table" => $table
        ]);
    }


    private static function onlinePaymentInitiate($data)
    {
        $postData = [];

        $postData['store_id']       = env('SSL_STORE_ID');
        $postData['store_passwd']   = env('SSL_STORE_PASSWORD');

        $postData['total_amount']   = $data['amount'];
        // $postData['currency']       = 'BDT';
        $postData['currency']       = getCurrency();
        $postData['tran_id']        = "ECOM_".uniqid();
        $postData['success_url']    = route('dc.payment-success');
        $postData['fail_url']       = route('dc.payment-fail');
        $postData['cancel_url']     = route('dc.payment-cancel');

        $postData['emi_option']     = 0;

        $postData['cus_name']       = $data['name'];
        $postData['cus_email']      = $data['email'];
        $postData['cus_add1']       = $data['address'];
        $postData['cus_city']       = "";
        $postData['cus_postcode']   = "";
        $postData['cus_country']    = $data['country'];
        $postData['cus_phone']      = $data['phone'];
        $postData['cus_fax']        = "";

        $postData['shipping_method'] = "NO";
        $postData['num_of_item']    = 0;

        $postData['value_a']        = $data['order_id'];
        $postData['value_b']        = $data['amount'];

        $postData['cart'] = json_encode(array(
            array("product" => "Order Purchase", "amount" =>  $data['amount'])
        ));
        $postData['product_name'] = "Fee";
        $postData['product_category'] = "E-Commerce";
        $postData['product_profile'] = "physical-goods";
        $postData['product_amount'] = $data['amount'];
        $postData['vat'] = 0;
        $postData['discount_amount'] = 0;
        $postData['convenience_fee'] = 0;


        $direct_api_url = env('SSL_PAYMENT_URL');
       // $direct_api_url = "https://securepay.sslcommerz.com/gwprocess/v4/api.php";
       // $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";

        // dd($postData);
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $direct_api_url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


        $content = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($code == 200 && !(curl_errno($handle))) {
            curl_close($handle);
            $sslcommerzResponse = $content;
        } else {
            curl_close($handle);
            echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
            exit;
        }

        # PARSE THE JSON RESPONSE
        $sslcz = json_decode($sslcommerzResponse, true);

        if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
            # THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
            # echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";
            echo "<meta http-equiv='refresh' content='0;url=" . $sslcz['GatewayPageURL'] . "'>";
            # header("Location: ". $sslcz['GatewayPageURL']);
            exit;
        } else {
            echo "JSON Data parsing error!";
        }
    }

    public static function paymentSuccess(Request $request)
    {
        $data = $request->all();
        if($data['value_a']){

            Cart::instance(self::cartInstanceName())->destroy();


            Order::where("id", $data['value_a'])->update([
                "order_status" => 'processing',
                "payment_status" => 'complete',
            ]);
            OrderPaymentMeta::where("order_id", $data['value_a'])->update([
                'gateway_return' => json_encode($data),
                'gateway_trx_id' => $data['tran_id'],
            ]);


            session()->flash('success', 'Your order has been placed successfully');

        }
        
        return redirect(route('my-profile') . '#orders');
    }
    public static function paymentCancel(Request $request)
    {
        $data = $request->all();
        if($data['value_a']){

            Cart::instance(self::cartInstanceName())->destroy();


            Order::where("id", $data['value_a'])->update([
                "order_status" => 'rejected',
                "payment_status" => 'failed',
            ]);

            session()->flash('success', 'Your order has been placed');
            session()->flash('error', 'Online payment has been canceled');

        }
        return redirect(route('my-profile') . '#orders');


    }
    public static function paymentFail(Request $request)
    {
        $data = $request->all();
        Cart::instance(self::cartInstanceName())->destroy();

        if($data['value_a']){
            Order::where("id", $data['value_a'])->update([
                "order_status" => 'pending',
                "payment_status" => 'failed',
            ]);

            session()->flash('success', 'Your order has been placed');
            session()->flash('error', 'Online payment has been failed.');
        }
        return redirect(route('my-profile') . '#orders');

    }
}
