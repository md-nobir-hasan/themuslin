<?php

namespace App\Action;

use Modules\Campaign\Entities\CampaignProduct;
use Modules\Campaign\Entities\CampaignSoldProduct;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use App\Helpers\CartHelper;
use App\Shipping\ShippingMethodOption;
use Gloudemans\Shoppingcart\Facades\Cart;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCoupon;
use Modules\Product\Entities\ProductInventory;
use App\Shipping\ShippingMethod;
use App\Tax\CountryTax;
use App\Tax\StateTax;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CartAction {
    /**
     * Calculate total of all the product's price in cart
     */
    public static function getCartTotalAmount($cart_items, $products)
    {
        $subtotal = 0;
        foreach ($cart_items as $key => $items) {
            $product = $products->find($key);
            foreach ($items as $item) {
                $quantity = $item['quantity'];
                $price = $item['attributes']['price'] ?? optional($product)->sale_price;
                $subtotal += $price * $quantity;
            }
        }
        return $subtotal;
    }

    /**
     * Subtract coupon from total amount if coupon is applied and available
     * @param Request $request
     * @param $subtotal (Subtotal + Tax)
     * @param Product $products - All products' DB collection from cart
     * @param string $return_type
     * @return float|int|mixed|null
     */
    public static function calculateCoupon(Request $request, $subtotal, $products, $return_type = 'TOTAL')
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
            $discount_total = $subtotal / 100 * $coupon_amount; // not needed
        }elseif ($discount_on == 'shipping') {
            $discount_total = $subtotal / 100 * $coupon_amount; // not needed
        } elseif ($discount_on == 'category') {
            $categories = $coupon->discount_on_details;
            $categories = (array) json_decode($coupon->discount_on_details);
            $category = $categories[0];
            $product_ids = $products->where('category_id', $category)->pluck('id');

            if (count($product_ids) < 1) {
//                if ($return_type == 'DISCOUNT') {
//                    return 0;
//                }
//                return $total;
                return 0;
            }

            $subtotal = CartAction::getCartItemTotalPrice($product_ids, $products);
        } elseif ($discount_on == 'subcategory') {
            $subcategories = $coupon->discount_on_details;
            $subcategories = (array) json_decode($coupon->discount_on_details);
            $cart_product_ids = $products->pluck('id');
            $products = Product::whereJsonContains('sub_category_id', $subcategories)->whereIn('id', $cart_product_ids)->get();
            $product_ids = $products->pluck('id');
            if (count($product_ids) < 1) {
//                if ($return_type == 'DISCOUNT') {
//                    return 0;
//                }
//                return $total;
                return 0;
            }
            $subtotal = CartAction::getCartItemTotalPrice($product_ids, $products);
        } elseif ($discount_on == 'product') {
            $product_ids = $coupon->discount_on_details;
            $product_ids = (array) json_decode($coupon->discount_on_details);
            $products = Product::whereIn('id', $product_ids)->get();

            if (is_null($products)) {
//                if ($return_type == 'DISCOUNT') {
//                    return 0;
//                }
//                return $total;
                return 0;
            }

            $subtotal = CartAction::getCartItemTotalPrice($product_ids, $products);
            if ($subtotal < 1) {
                return 0;
            }
        }

        // calculate based on coupon type
        if ($coupon_type === 'percentage') {
            $discount_total = $subtotal / 100 * $coupon_amount;
        } elseif ($coupon_type === 'amount') { # =====
            $discount_total = $coupon_amount;
        }

        if ($return_type == 'DISCOUNT') {
            return $discount_total;
        }

        $total = $subtotal - $discount_total;

        return $total;
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

    /**
     * From requested item quantity get only the available quantity from stock
     */
    public static function getAvailableQuantity($item_id, $quantity=null)
    {
        $inventory_item = ProductInventory::where('product_id', $item_id)->first();
        $stock_count = 0;

        if (!is_null($inventory_item) && $inventory_item->count()) {
            $stock_count = $inventory_item->stock_count;
        }

//        if ($stock_count - $quantity >= 0) {
//            return $quantity;
//        }

        return $stock_count;
    }

    /**
     * From requested item quantity get only the available quantity from campaign
     *      and return the campaign quantity and remaining quantity
     */
    public static function getCampaignQuantity($item_id, $requested_quantity)
    {
        $campaign_product = CampaignProduct::where('product_id', $item_id)
            ->whereRelation('campaign', 'status', 'publish')
            ->first();

        // if item not in campaign
        if (!$campaign_product) {
            return [
                'campaign_quantity' => 0,
                'campaign_price' => NULL,
                'remaining_quantity' => $requested_quantity
            ];
        }

        $campaign_sold_count = optional(CampaignSoldProduct::where('product_id', $item_id)->first())->sold_count ?? 0;
        $campaign_price = $campaign_product->campaign_price;
        $units_for_sale = $campaign_product->units_for_sale - $campaign_sold_count;

        // if all requested quantity is available in campaign
        if ($units_for_sale >= $requested_quantity) {
            return [
                'campaign_quantity' => $requested_quantity,
                'campaign_price' => $campaign_price,
                'remaining_quantity' => 0
            ];
        }

        // if all requested quantity is NOT available in campaign
        return [
            'campaign_quantity' => $units_for_sale,
            'campaign_price' => $campaign_price,
            'remaining_quantity' => $units_for_sale > 0 ? $requested_quantity - $units_for_sale : 0,
        ];
    }

    /**
     * Return default shipping option
     */
    public static function getDefaultShipping()
    {
        return ShippingMethod::where('is_default', 1)->with('availableOptions')->first();
    }

    /**
     * Return default shipping cost
     */
    public static function getDefaultShippingCost()
    {
        $default_shipping_cost = ShippingMethod::where('is_default', 1)->first();
        if (!is_null($default_shipping_cost) && $default_shipping_cost->id) {
            $default_shipping_cost = optional($default_shipping_cost->options)->cost ?? 0;
        }
        return $default_shipping_cost;
    }

    /**
     * Get selected shipping method cost
     */
    public static function getSelectedShippingCost($shipping_id, $subtotal, $coupon = '')
    {
        $cost = 0;
        $is_taxable = false;
        $shipping_option = ShippingMethodOption::where('shipping_method_id', $shipping_id)
            ->where('status', 1)
            ->first();

        $default_shipping = optional(self::getDefaultShipping())->cost ?? 0;

        if (is_null($shipping_option)) {
            return $default_shipping;
        }

        $is_taxable = (bool) $shipping_option->tax_status;
        $minimum_order_amount = $shipping_option->minimum_order_amount;

        $setting_preset = $shipping_option->setting_preset;

        if ($setting_preset == 'none') {
            $cost = $shipping_option->cost;
        } elseif ($setting_preset == 'min_order') {
            // if minimum order quota filled
            if ($subtotal >= $shipping_option->minimum_order_amount) {
                $cost = $shipping_option->cost;
            }
        } elseif ($setting_preset == 'min_order_or_coupon') {
            // if minimum order quota filled OR coupon matched
            if ($subtotal >= $shipping_option->minimum_order_amount || $coupon == $shipping_option->coupon) {
                $cost = $shipping_option->cost;
            }
        } elseif ($setting_preset == 'min_order_and_coupon') {
            // if minimum order quota filled AND coupon matched
            if ($subtotal >= $shipping_option->minimum_order_amount && $coupon == $shipping_option->coupon) {
                $cost = $shipping_option->cost;
            }
        }

        return [
            'cost' => $cost,
            'is_taxable' => $is_taxable,
        ];
    }

    /**
     * Check if coupon is valid
     */
    public function isCouponValid($coupon_code)
    {
        $coupon = ProductCoupon::where('code', $coupon_code)->first();
        if ($coupon) {
            $coupon_amount = $coupon->discount;
            $coupon_type = $coupon->discount_type;
        }
    }

    public static function getCartInfo()
    {
        $all_cart_items = CartHelper::getItems();
        $all_cart_ids = array_keys($all_cart_items);
        $products = Product::whereIn('id', $all_cart_ids)->get();
        $cart_items_count = CartHelper::getTotalQuantity();
        $subtotal = CartAction::getCartTotalAmount($all_cart_items, $products);

        $result = [];
        foreach ($all_cart_items as $key => $all_products) {
            $product_item = $products->find($key);

            foreach ($all_products as $product) {
                $image_details = get_attachment_image_by_id($product_item->image, 'grid');
                $image_url = $image_details['img_url'];

                if (isset($product['attributes'])) {
                    $product['attributes']['price'] = $product_item->sale_price;
                }

                $result[] = [
                    'name' => optional($product_item)->title,
                    'product_count' => isset($product['quantity']) ? $product['quantity'] : 0,
                    'price' => float_amount_with_currency_symbol(optional($product_item)->price),
                    'sale_price' => float_amount_with_currency_symbol(optional($product_item)->sale_price),
                    'product_attribute' => json_encode($product),
                    'image_src' => $image_url ? $image_url : '',
                ];
            }
        }

        return [
            'cart_items_count' => $cart_items_count,
            'result' => $result,
            'subtotal' => float_amount_with_currency_symbol($subtotal),
        ];
    }

    public static function storeItemSoldCount($cart_items, $products)
    {

        foreach ($cart_items as $key => $items) {
            $total_item_quantity = $items[0]["quantity"];
            $total_item_price = $items[0]["attributes"]["price"];
            $product = $products->find($key);
            $campaign_product = getCampaignProductById($key);

            if($campaign_product){
                $campaignSoldProductInfo = CampaignSoldProduct::where("product_id", $key)->first();
                CampaignSoldProduct::updateOrCreate(["product_id" => $key],
                    [
                        "sold_count" => optional($campaignSoldProductInfo)->sold_count ?? 0,
                        "total_amount" => optional($campaignSoldProductInfo)->total_amount ?? 0,
                    ]
                );
            }

            $product->update(['sold_count' => $total_item_quantity]);
        }
        return true;
    }

    /**
     * Validate cart item quantity against stock count from the Inventory
     */
    public static function validateItemQuantity()
    {
        $all_cart_items = CartHelper::getItems();
        $item_ids = array_keys($all_cart_items);
        $cart_items_inventory_count = ProductInventory::select('id', 'product_id', 'stock_count')->whereIn('product_id', $item_ids)->get();
        $products = Product::whereIn('id', $item_ids)->get();

        $cart_stock_details = [];
        $out_of_stock_errors = [];

        foreach ($all_cart_items as $product_id => $product_items) {
            $cart_item_count = 0;
            $inventory_item = $cart_items_inventory_count->filter(function($item) use ($product_id) {
                return $item->product_id == $product_id;
            })->first();

            $stock_inventory_count =
                $cart_items_inventory_count->where('product_id', $product_id)->sum('stock_count')
                -
                $cart_items_inventory_count->where('product_id', $product_id)->sum('sold_count');

            foreach ($product_items as $key => $product) {
                $cart_item_count += $product['quantity'] ?? 0;
            }

            $cart_stock_details[] = [
                'item_id' => $product_id,
                'cart_count' => $cart_item_count,
                'stock_count' => $stock_inventory_count,
                'status' => $stock_inventory_count >= $cart_item_count ? 'valid' : 'invalid',
            ];
        }

        // generate errors messages
        foreach ($cart_stock_details as $item_info) {
            if ($item_info['stock_count'] == 0) {
                $out_of_stock_errors[] = optional($products->find($item_info['item_id']))->title . ' ' . __('is out of stock');
            } else {
                if ($item_info['status'] == 'invalid') {
                    $out_of_stock_errors[] = __('You asked for')
                        . ' ' . $item_info['cart_count'] . ' ' . __('units of')
                        . ' ' . optional($products->find($item_info['item_id']))->title . __(', but only')
                        . ' ' . $item_info['stock_count'] . ' ' . __('items available in stock');
                }
            }
        }

        // keep only available quantity in the cart
        CartAction::cleanCart($cart_stock_details);

        return [
            'details' => $cart_stock_details,
            'messages' => $out_of_stock_errors
        ];
    }

    public static function cleanCart($cart_info, $update_stock = false)
    {
        $invalid_items = $cart_info;
        $items_to_remove = [];
        foreach ($invalid_items as $key => $cart_info_item) {
            if ($cart_info_item['status'] == 'valid') {
                unset($invalid_items[$key]);
            } else {
                $item_to_remove = $cart_info_item['cart_count'] - $cart_info_item['stock_count'];
                $items_to_remove[$cart_info_item['item_id']] = $item_to_remove;
            }
        }

        $all_cart_items = CartHelper::getItems();
        foreach ($items_to_remove as $id => $count) {
            // remove from cart
            if (isset($all_cart_items[$id])) {
                $cart_info_stock = 0;

                foreach ($cart_info as $data) {
                    if ($data['item_id'] == $id) {
                        $cart_info_stock = $data['stock_count'];
                    }
                }
                $all_cart_items[$id][0]['quantity'] = $cart_info_stock;
                CartHelper::update($id, $all_cart_items[$id][0]['quantity'], $all_cart_items[$id][0]['attributes']);

                foreach ($all_cart_items[$id] as $key => $cart_item_i) {
                    if ($key != 0) {
                        CartHelper::remove($cart_item_i['id'], $cart_item_i['attributes']);
                    }
                }
            }

            // subtract from inventory
            if ($update_stock) {
                $product_inventory = ProductInventory::where('product_id', $id)->first();
                if ($product_inventory) {
                    if ($product_inventory->stock_count - $count > 0) {
                        $product_inventory->update(['stock_count' => $product_inventory->stock_count - $count]);
                    } else {
                        $product_inventory->update(['stock_count' => 0]);
                    }
                }
            }
        }

        return true;
    }

    public static function getCartItemQuantity(array $product_ids)
    {
        $total_quantity = 0;
        foreach ($product_ids as $product_id) {
            $total_quantity += CartHelper::getItemQuantity($product_id);
        }
        return $total_quantity;
    }

    /**
     * Calculate prices of the given product given products
     * @param array $product_ids
     * @param  $products
     */
    public static function getCartItemTotalPrice($product_ids, $products)
    {
        // now first of all need to get all cart items and take only available product for this coupon
        $cart_items = Cart::content();
        $product_price = 0;
        foreach($cart_items as $item){
            if (in_array($item->id,$product_ids)){
                $product_price += $item->price;
            }
        }


        $total_price = 0;
        foreach ($product_ids as $product_id) {
            $cart_price = CartHelper::getItemPrice($product_id);

            // if cart item has no attributes, there will be no price. So the price needs to
            // collected from DB collection
            if ($cart_price == 0) {
                $quantity = CartAction::getCartItemQuantity([$product_id]);
                $item = $products->find($product_id);

                if (!$item) {
                    $item = Product::find($product_id);
                }

                if ($item) {
                    $item_price = $item->sale_price;
                    $cart_price = $item_price * $quantity;
                } else {
                    // Works as a fallback -
                    // Even if the product is not found in the DB.
                    // A case could be that the product was removed
                    // from the DB and cannot be found at the time
                    // when this functions runs
                    $cart_price = 0;
                }
            }
            $total_price += $cart_price;
        }

        return $total_price;
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
                $state_tax = StateTax::where('state_id', $user_state->id)->first();
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

    public static function getCheckoutTaxAmount($subtotal, $country, $state)
    {
        $tax = 0;
        $tax_percentage = 0;

        if ($subtotal > 0) {
            $state = State::find($state); // should have filter here
            $country = Country::find($country); // should have filter here

            //get state/country tax
            if (!is_null($state)) {
                $state_tax = StateTax::where('state_id', $state->id)->first();
                if(!is_null($state_tax)){
                     $tax_percentage = $state_tax->tax_percentage ?? 0;
                }else{
                    $country_tax = CountryTax::where('country_id', $country->id)->first();
                    $tax_percentage = $country_tax->tax_percentage ?? 0;
                }
               
            } elseif (!is_null($country)) {
                $country_tax = CountryTax::where('country_id', $country->id)->first();
                $tax_percentage = $country_tax->tax_percentage ?? 0;
            }

            // if user's state/country as any tax
            $tax = $tax_percentage ? $subtotal / 100 * $tax_percentage : 0;
        }

        return $tax;
    }

    public static function validateSelectedShipping($shipping_id, $coupon = '') : bool
    {
        $all_cart_items = CartHelper::getItems();
        $all_cart_ids = array_keys($all_cart_items);
        $products = Product::whereIn('id', $all_cart_ids)->get();
        $cart_total = CartAction::getCartTotalAmount($all_cart_items, $products);

        $selected_shipping_option = ShippingMethodOption::where('shipping_method_id', $shipping_id)
            ->where('status', 1)
            ->first();

        if (is_null($selected_shipping_option)) {
            return false;
        }

        $setting_preset = $selected_shipping_option->setting_preset;
        $minimum_order_amount = $selected_shipping_option->minimum_order_amount;
        $coupon_matched = isset($selected_shipping_option->coupon) && $selected_shipping_option->coupon == $coupon;

        // check presets
        if ($setting_preset == 'min_order') {
            if ($cart_total < $minimum_order_amount) {
                return false;
            }
        } elseif ($setting_preset == 'min_order_or_coupon') {
            if ($cart_total < $minimum_order_amount && !$coupon_matched) {
                return false;
            }
        } elseif ($setting_preset == 'min_order_and_coupon') {
            if ($cart_total < $minimum_order_amount || !$coupon_matched) {
                return false;
            }
        }

        return true;
    }
}
