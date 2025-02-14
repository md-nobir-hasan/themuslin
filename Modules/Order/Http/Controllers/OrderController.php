<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Requests\Frontend\SubmitCheckoutRequest;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Services\CheckoutCouponService;
use Modules\Order\Services\OrderService;
use Modules\Product\Entities\Product;
use Modules\ShippingModule\Entities\ShippingMethod;
use Modules\TaxModule\Entities\CountryTax;
use Modules\TaxModule\Entities\StateTax;
use Throwable;

class OrderController extends Controller
{

    /**
     * @throws Throwable
     */
    public function checkout(SubmitCheckoutRequest $request){
        $data = $request->validated();

        return OrderService::testOrder($data);
    }

    public function sync_product_coupon(Request $request): JsonResponse
    {
        $all_cart_items = Cart::instance("default")->content();
        $products = Product::with("category", "subCategory", "childCategory")->whereIn('id', $all_cart_items?->pluck("id")?->toArray())->get();
        $subtotal = Cart::instance("default")->subtotal();

        $coupon_amount_total = CheckoutCouponService::calculateCoupon($request, $subtotal, $products, 'DISCOUNT');

        if ($coupon_amount_total == 0) {
            return response()->json(['type' => 'error', 'msg' => 'Please enter a valid coupon code']);
        }

        if ($coupon_amount_total) {
            return response()->json([
                'type' => 'success',
                'coupon_amount' => toFixed($coupon_amount_total),
                'msg' => __('Coupon applied successfully')
            ]);
        }

        return response()->json(['type' => 'danger', 'coupon_amount' => 0]);
    }

    private function get_product_shipping_tax_for_coupon($request): array
    {
        $shipping_cost = 0;
        $product_tax = 0;

        if ($request['state'] && $request['country']) {
            $product_tax = StateTax::where(['country_id' => $request['country'], 'state_id' => $request['state']])->select('id', 'tax_percentage')->first();
            if ($product_tax) {
                $product_tax = $product_tax->toArray()['tax_percentage'];
            }
        } else {
            $product_tax = CountryTax::where('country_id', $request['country'])->select('id', 'tax_percentage')->first();
            if ($product_tax) {
                $product_tax = $product_tax->toArray()['tax_percentage'];
            }
        }

        $shipping = ShippingMethod::find($request['shipping_method']);
        $shipping_option = $shipping->options ?? null;

        if ($shipping_option != null && $shipping_option?->tax_status == 1) {
            $shipping_cost = $shipping_option?->cost + (($shipping_option?->cost * $product_tax) / 100);
        } else {
            $shipping_cost = $shipping_option?->cost;
        }

        $data['product_tax'] = $product_tax;
        $data['shipping_cost'] = $shipping_cost;

        return $data;
    }

    public function orderTracking($orderId){

    }
}
