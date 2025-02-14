<?php

namespace Modules\MobileApp\Http\Controllers\Api;

use App\Exceptions\NotArrayObjectException;
use App\Http\Controllers\PaymentGatewayController;
use Illuminate\Routing\Controller;
use App\Http\Requests\Frontend\SubmitCheckoutRequest;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderTrack;
use Modules\Order\Entities\SubOrder;
use Modules\Order\Services\OrderService;
use Modules\TaxModule\Entities\TaxClassOption;
use Modules\TaxModule\Services\CalculateTaxBasedOnCustomerAddress;
use Modules\TaxModule\Services\CalculateTaxServices;
use Modules\Vendor\Entities\Vendor;
use Throwable;

class ApiOrderController extends Controller
{
    /**
     * @throws NotArrayObjectException
     * @throws Throwable
     */
    public function calculateTaxAmount(Request $request){
        $data = $request->validate([
            "cart_items" => "required"
        ]);

        $cart_items = (array) json_decode($data['cart_items']);
        $carts = collect($cart_items);
        $itemsTotal = null;
        $enableTaxAmount = !CalculateTaxServices::isPriceEnteredWithTax();
        $shippingTaxClass = TaxClassOption::where("class_id", get_static_option("shipping_tax_class"))->sum("rate");
        $tax = CalculateTaxBasedOnCustomerAddress::init();
        $uniqueProductIds = $carts->pluck("id")->unique()->toArray();

        $country_id = $request->country_id ?? 0;
        $state_id = $request->state_id ?? 0;
        $city_id = $request->city_id ?? 0;

        if(empty($uniqueProductIds)){
            $taxProducts = collect([]);
        }else{
            if(CalculateTaxBasedOnCustomerAddress::is_eligible()){
                $taxProducts = $tax
                    ->productIds($uniqueProductIds)
                    ->customerAddress($country_id, $state_id, $city_id)
                    ->generate();
            }else{
                $taxProducts = collect([]);
            }
        }


        $carts = $carts->groupBy("options.vendor_id");
        $vendors = Vendor::with("shippingMethod","shippingMethod.zone")
            ->whereIn("id", array_keys($carts->toArray()))->get();

        $taxInformation = [];

        foreach($carts as $key => $vendor){
            $c_vendor = $vendors->find($key);
            $adminShippingMethod = null;
            $adminShopManage = null;
            $subtotal = null;
            $default_shipping_cost = null;
            $v_tax_total = 0;

            if(empty($key)){
                $adminShippingMethod = \Modules\ShippingModule\Entities\AdminShippingMethod::with("zone")->get();
                $adminShopManage = \App\AdminShopManage::latest()->first();
            }

            foreach($vendor as $item) {
                $taxAmount = $taxProducts->where("id" , $item->id)->first();

                if(!empty($taxAmount)){
                    $taxAmount->tax_options_sum_rate = $taxAmount->tax_options_sum_rate ?? 0;
                    $price = calculatePrice($item->price, $taxAmount);

                    $regular_price = ($item->options->regular_price ?? false) ? calculatePrice($item->options->regular_price, $item->options) : 0;
                    $v_tax_total += calculatePrice($item->price, $taxAmount, "percentage") * $item->qty;
                }else{
                    $price = calculatePrice($item->price, $item->options);

                    $regular_price = ($item->options->regular_price ?? false) ? calculatePrice($item->options->regular_price, $item->options) : 0;
                }

                $taxInformation[$key ?? 'admin']["tax_amount"] = $v_tax_total ?? 0;

                $taxInformation[$key ?? 'admin']["products"][] = [
                    "id" => $item->id,
                    "price" => $price,
                    "old_price" => $item->price
                ];

                $subtotal += $price * $item->qty;
                $itemsTotal += $price * $item->qty;
            }

            $taxInformation[$key ?? 'admin']["sub_total"] = $subtotal;
        }

        return $taxInformation;
    }

    /**
     * @throws Throwable
     */
    public function placeOrder(SubmitCheckoutRequest $data){
        $data = $data->validated();

        $cart_items = (array) json_decode($data['cart_items']);
        $shipping_cost = (array) json_decode($data['shipping_cost']);
        $data['cart_items'] = $cart_items;
        $data['shipping_cost'] = $shipping_cost;

        return OrderService::apiOrder($data);
    }

    public function update_payment_status(Request $request){
        // first, we need to check app_secret_key
        // get app_secret_key from an admin panel
        $app_secret_key = get_static_option("app_secret_key");
        // retrieve app secret key from header
        $request_secret_key = $request->header("app-secret-key");

        // now need to check a request secret key
        if($request_secret_key !== $app_secret_key){
            return response()->json([
                "msg" => __("Invalid request for update payment status"),
            ]);
        }

        // write a query for getting order information from a database
        $order = Order::where("id", $request->order_id)
            ->whereNot("payment_status", "complete")->first();

        // check order exists or not if not then return error
        if(empty($order)){
            return response()->json([
                "msg" => __("No order found"),
                "type" => "danger"
            ], 404);
        }

        // now check transaction id is matched for this order or not
        if(!\Hash::check($order->transaction_id, $request->order_secret_id)){
            return response()->json([
                "msg" => __("Invalid order information"),
                "type" => "danger"
            ], 403);
        }

        $order_info = [
            "success" => $request->success,
            "type" => $request->type,
            "order_id" => $request->order_id,
            "total_amount" => $request->total_amount,
            "transaction_id" => $order->transaction_id,
            "status" => $request->status
        ];

        return [
            "data" => PaymentGatewayController::common_ipn_data($order_info, false)
        ];
    }

    public function orderList(){
        $user_id = auth("sanctum")->user()->id;

        // first of all we need to get all sub order for login user
        return Order::with([
                "paymentMeta",
                "orderTrack" => function ($query){
                    $query->latest('id')->limit(1);
                }
            ])->where('user_id', $user_id)
            ->orderBy('id', 'DESC')->paginate(10);
    }

    public function orderDetails($item)
    {
        $orders = SubOrder::with([
            "order",
            "vendor",
            "orderItem",
            "orderItem.product",
            "orderItem.product.campaign_product",
            "orderItem.variant",
            "orderItem.variant.productColor",
            "orderItem.variant.productSize"
        ])->where("order_id", $item)->get();
        $payment_details = Order::with("address","address.country","address.state","address.cityInfo","paymentMeta")->find($item);

        if(empty($payment_details)){
            return response()->json([
                "msg" => __("No Order Found"),
            ], 404);
        }

        $orderTrack = OrderTrack::where("order_id", $payment_details->id)->orderByDesc("id")->first();

        $orders->transform(function ($item){

            $item->orderItem?->transform(function ($subItem){
                if(!empty($subItem->product->image) && is_object($subItem->product?->image)) {
                    $product_image = $subItem->product->image;
                    unset($subItem->product->image);
                    $subItem->product->image = render_image($product_image, render_type: 'path');
                }

                $attr_image = $subItem->variant?->attr_image;

                if(!empty($subItem->variant?->attr_image)){
                    unset($subItem->variant->attr_image);

                    $subItem->variant->attr_image = render_image($attr_image, render_type: 'path');
                }

                return $subItem;
            });

            return $item;
        });

        return [
            "order" => $orders,
            "payment_details" => $payment_details,
            "order_track" => $orderTrack
        ];
    }
}