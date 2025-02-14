<?php

namespace Modules\Order\Services;

use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Modules\Coupon\Entities\ProductCoupon;

class CheckoutCouponService
{
    private static function get_category_product_ids($products, $category, $discount_on): array
    {
        $prd_ids = [];



        foreach($products as $product){

            if ($product?->category?->id === $category && $discount_on == "category"){
                $prd_ids[] = $product->id;
            }elseif ($product?->subCategory?->id === $category && $discount_on == "subcategory"){
                $prd_ids[] = $product->id;
            }elseif ($discount_on == "childcategory"){

                if($product->childCategory?->id === intval($category[0])){
                    $prd_ids[] = $product->id;
                }
            }
        }

        return $prd_ids;
    }

    private function get_sub_category_product_ids($products, $childCategories){
        $prd_ids = [];

        foreach($products as $product){
            if (in_array($product?->childCategory?->pluck("id"), $childCategories)){
                $prd_ids[] = $product->id;
            }
        }

        return $prd_ids;
    }

    /**
     * Calculate prices of the given product given products
     * @param array $product_ids
     * @param  $products
     * @return float|int
     */
    public static function getCartItemTotalPrice(array $product_ids, $products): float|int
    {
        // now first of all need to get all cart items and take only available product for this coupon
        $cart_items = Cart::content();
        $total_price = 0;
        foreach($cart_items as $item){
            if (in_array($item->id,$product_ids)){
                $total_price += $item->price * $item->qty;
            }
        }

        return $total_price;
    }

    /**
     * Subtract coupon from total amount if coupon is applied and available
     * @param object $request
     * @param $subtotal (Subtotal + Tax)
     * @param $products - All products' DB collection from cart
     * @param string $return_type
     * @param null $shippingAmount
     * @param null $purpose
     * @return float|int|mixed|null
     */
    public static function calculateCoupon(object $request, $subtotal, $products, string $return_type = 'TOTAL', $shippingAmount = null, $purpose = null): mixed
    {
        if (empty($request->coupon)) {
            if ($return_type == 'DISCOUNT') {
                return 0;
            }

            return $subtotal;
        }

        $total = $subtotal;
        $coupon_code = $request->coupon;
        $coupon_amount = null;
        $coupon_type = null;
        $discount_total = 0;

        // if coupon input given
        if ($coupon_code) {
            $coupon = ProductCoupon::where('code', $coupon_code)->where('status', 'publish')->first();

            if (is_null($coupon)) {
                if ($return_type == 'DISCOUNT') {
                    return 0;
                }
                return $total;
            }

            // if expired
            if ($coupon && !Carbon::parse($coupon->expire_date)->greaterThan(\Carbon\Carbon::today())) {
                if ($return_type == 'DISCOUNT') {
                    return 0;
                }
                return $total;
            }

            $coupon_amount = $coupon->discount;
            $coupon_type = $coupon->discount_type;
        }

        $discount_on = $coupon->discount_on;

        if ($purpose == 'type')
        {
            return [
                'discount_on' => $discount_on,
                'coupon_type' => $coupon_type,
                'coupon_amount' => $coupon_amount,
                'coupon_object' => $coupon
            ];
        }

        if ($discount_on == 'all') {
            $discount_total = $coupon_amount; // not needed
        }elseif ($discount_on == 'shipping') {
            $discount_total = $coupon_amount; // not needed
        } elseif ($discount_on == 'category') {
            $categories = (array) json_decode($coupon->discount_on_details);
            $category = (int) $categories[0];
            $product_ids = self::get_category_product_ids($products, $category, $discount_on);

            if (count($product_ids) < 1) {
                return 0;
            }

            $subtotal = CheckoutCouponService::getCartItemTotalPrice($product_ids, $products);



        } elseif ($discount_on == 'subcategory') {
            $categories = (array) json_decode($coupon->discount_on_details);
            $category = (int) $categories[0];
            $product_ids = self::get_category_product_ids($products, $category, $discount_on);

            // dump($product_ids);
            // exit();

            if (count($product_ids) < 1) {
                return 0;
            }

            $subtotal = CheckoutCouponService::getCartItemTotalPrice($product_ids, $products);

            // dump($subtotal);

        } elseif ($discount_on == 'childcategory') {
            $categories = (array) json_decode($coupon->discount_on_details);
           
            $product_ids = self::get_category_product_ids($products, $categories, $discount_on);

            if (count($product_ids) < 1) {
                return 0;
            }

            $subtotal = CheckoutCouponService::getCartItemTotalPrice($product_ids, $products);

        } elseif ($discount_on == 'product') {
            $product_ids = (array) json_decode($coupon->discount_on_details);

            if (count($product_ids) < 1) {
                return 0;
            }

            $subtotal = CheckoutCouponService::getCartItemTotalPrice($product_ids, $products);
        }

        // calculate based on coupon type
        $subtotal = str_replace(',','',$subtotal);
        if ($coupon_type === 'percentage') {
            $discount_total = $subtotal / 100 * $coupon_amount;
        } elseif ($coupon_type === 'amount') { # =====
            $discount_total = $coupon_amount;
        }

        return $discount_total;
    }
}
