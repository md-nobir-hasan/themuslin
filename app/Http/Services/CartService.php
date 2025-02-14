<?php

namespace App\Http\Services;

use App\Action\CartAction;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use App\Tax\CountryTax;
use App\Tax\StateTax;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCoupon;

class CartService
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
                foreach($product?->childCategory as $childCategory){
                    if (in_array($childCategory->id,$category)){
                        $prd_ids[] = $product->id;
                    }
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
     * @param Request $request
     * @param $subtotal (Subtotal + Tax)
     * @param $products - All products' DB collection from cart
     * @param string $return_type
     * @return float|int|mixed|null
     */
    public static function calculateCoupon(Request $request, $subtotal, $products, string $return_type = 'TOTAL', $shippingAmount = null): mixed
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

            $subtotal = CartService::getCartItemTotalPrice($product_ids, $products);
        } elseif ($discount_on == 'subcategory') {
            $categories = (array) json_decode($coupon->discount_on_details);
            $category = (int) $categories[0];
            $product_ids = self::get_category_product_ids($products, $category, $discount_on);

            if (count($product_ids) < 1) {
                return 0;
            }

            $subtotal = CartService::getCartItemTotalPrice($product_ids, $products);
        } elseif ($discount_on == 'childcategory') {
            $categories = (array) json_decode($coupon->discount_on_details);
            $product_ids = self::get_category_product_ids($products, $categories, $discount_on);

            if (count($product_ids) < 1) {
                return 0;
            }

            $subtotal = CartService::getCartItemTotalPrice($product_ids, $products);
        } elseif ($discount_on == 'product') {
            $product_ids = (array) json_decode($coupon->discount_on_details);

            if (count($product_ids) < 1) {
                return 0;
            }

            $subtotal = CartService::getCartItemTotalPrice($product_ids, $products);
        }

        // calculate based on coupon type
        if ($coupon_type === 'percentage') {
            dd(($subtotal ?? $total));
            $discount_total = ($subtotal ?? $total) / 100 * $coupon_amount;
        } elseif ($coupon_type === 'amount') { # =====
            $discount_total = $coupon_amount;
        }

        if ($return_type == 'DISCOUNT') {
            return $discount_total;
        }

        return $subtotal - $discount_total;
    }

    public static function getCouponAmount(Request $request, $subtotal)
    {
        $coupon_code = $request->coupon;
        $coupon_amount = null;
        $coupon_type = null;

        // if coupon available
        if ($coupon_code) {
            $coupon = ProductCoupon::where('code', $coupon_code)->first();
            if (!$coupon) {
                return false;
            }
            $coupon_amount = $coupon->discount;
            $coupon_type = $coupon->discount_type;
        }

        // calculate based on coupon type
        if ($coupon_type == 'percentage') {
            return $subtotal / 100 * $coupon_amount;
        } elseif ($coupon_type == 'amount') {
            return $coupon_amount;
        }
    }


    public static function getDefaultTax($subtotal)
    {
        $tax = 0;
        $tax_percentage = 0;

        $user = getUserByGuard('web');
        $user_country = '';
        $user_state = '';

        if ($subtotal > 0 && $user) {
            $user_id = $user->id;
            $user_country = $user->country;

            $user_state = State::where('name', $user->state)->where('status', 'publish')->first();
            $user_country = Country::where('name', $user->country)->where('status', 'publish')->first();

            // get state/country tax
            if ($user_state) {
//                $state_tax = StateTax::where('state_id', $user_state->id)->first();
                $state_tax = 0;
                $tax_percentage = $state_tax->tax_percentage ?? 0;
            } elseif ($user_country) {
                $country_tax = CountryTax::where('country_id', $user_country->id)->first();
                $tax_percentage = $country_tax->tax_percentage ?? 0;
            }

            // if user's state/country as any tax
            $tax = $tax_percentage ? $subtotal / 100 * $tax_percentage : 0;
        }

        return [
            'tax' => $tax,
            'tax_percentage' => $tax_percentage,
        ];
    }

}