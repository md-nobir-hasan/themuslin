<?php

namespace Modules\CountryManage\Http\Controllers\Product;

use App\Action\CartAction;
use App\Helpers\CartHelper;
use App\Helpers\FlashMsg;
use App\Http\Services\CartService;
use App\Shipping\ShippingMethod;
use App\Shipping\ShippingMethodOption;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;

class ProductCartController extends Controller
{
    /**
     * Top-bar mini-cart info
     */
    public function getCartInfoAjax()
    {
        $all_cart_items = CartHelper::getItems();
        $products = Product::whereIn('id', array_keys($all_cart_items))->get();
        $subtotal = CartAction::getCartTotalAmount($all_cart_items, $products);

        return [
            'item_total' => CartHelper::getTotalQuantity(),
            'cart' => view('frontend.partials.mini-cart', compact('all_cart_items', 'products', 'subtotal'))->render(),
        ];
    }

    public function addToCartAjax(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'product_attributes' => 'nullable|array',
        ]);

        $product = Product::find($request->product_id);

        $attributes = [];
        $req_attributes = [];
        $product_price = 0;
        $additional_price = 0;
        $additional_price_arr = [];
        if ($request->product_attributes) {
            $req_attributes = (array) $request->product_attributes;

            foreach ($req_attributes as $key => $attribute) {
                $attributes[$attribute['type']] = $attribute['name'];
                $additional_price_arr[] = floatval($attribute['additional_price']);
            }

            $product_price = optional($product)->sale_price ?? 0;
            $attributes['price'] = $product_price + array_sum($additional_price_arr);
        }

        $remaining_quantity = $request->quantity;

        // if item is in campaign, add number of campaign quantity in the cart
        $campaign = CartAction::getCampaignQuantity($request->product_id, $remaining_quantity);

        if ($campaign['campaign_quantity'] > 0) {
            $attributes['type'] = 'Campaign Product';
            $attributes['price'] = $campaign['campaign_price'] + array_sum($additional_price_arr);

            CartHelper::add(
                $request->product_id,
                $campaign['campaign_quantity'],
                $attributes
            );

            $remaining_quantity = $campaign['remaining_quantity'];
        }

        $available_quantity = (int) CartAction::getAvailableQuantity($request->product_id, $remaining_quantity);

        // if remaining or non-campaign quantity
        if ($remaining_quantity) {
            // adjust attribute to normal product
            unset($attributes['type']);
            $attributes['price'] = $product_price + array_sum($additional_price_arr);

            if ($available_quantity > 0) {
                CartHelper::add(
                    $request->product_id,
                    $available_quantity,
                    $attributes
                );
            }
        }

        $response_data = FlashMsg::explain('success', __('Item added to cart'));
        $response_data['cart_info'] = CartAction::getCartInfo();

        if ($available_quantity != $remaining_quantity) {
            $response_data['quantity_msg'] = __('Requested quantity is not available. Only available quantity is added to cart');
        }

        return response()->json($response_data, 200);
    }

    public function cartStatus()
    {
        return response()->json([
            'total_price' => CartHelper::getAttributeTotal('price'),
            'all_items' => CartHelper::getItems(),
        ], 200);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'cart_data' => 'required|array',
            'cart_data.*.id' => 'required|exists:products,id',
            'cart_data.*.product_attribute' => 'nullable|array',
            'cart_data.*.quantity' => 'required|numeric',
        ]);

        $quantity_msg = [];

        foreach ($request->cart_data as $cart_item) {
            $product_attribute = isset($cart_item['product_attribute']) ? $this->formatAttributes($cart_item['product_attribute']) : [];
            $available_quantity = (int) CartAction::getAvailableQuantity($cart_item['id'], $cart_item['quantity']);

            $requested_quantity = $cart_item['quantity'];

            // if item is campaign product
            if (isset($product_attribute['type']) && $product_attribute['type'] == 'Campaign Product') {
                /**
                 * @note Requested campaign quantity that goes over the campaign quantity will be ignored
                 */
                $campaign = CartAction::getCampaignQuantity($cart_item['id'], $requested_quantity);

                if ($campaign['campaign_quantity'] > 0) {
                    CartHelper::update(
                        $cart_item['id'],
                        $campaign['campaign_quantity'],
                        $product_attribute
                    );
                }

                unset($product_attribute['type']);
                $requested_quantity = $campaign['remaining_quantity'];
            } else {
                // handle non-campaign quantity
                $available_quantity = (int) CartAction::getAvailableQuantity($cart_item['id'], $requested_quantity);

                if ($available_quantity > 0) {
                    CartHelper::update(
                        $cart_item['id'],
                        $available_quantity,
                        $product_attribute
                    );
                }
            }

            // if item quantity is not available in stock show message
            if ($available_quantity != $cart_item['quantity']) {
                $product = Product::find($cart_item['id']);
                $single_product_msg = $product->title ? __('of')." <b>$product->title</b>" : '';
                $quantity_msg[] = __("Requested quantity $single_product_msg is not available. Only available quantity is added to cart");
            }
        }

        // response view
        $default_shipping_cost = CartAction::getDefaultShippingCost();

        $all_cart_items = CartHelper::getItems();
        $products = Product::whereIn('id', array_keys($all_cart_items))->get();

        $subtotal = CartAction::getCartTotalAmount($all_cart_items, $products);
        $subtotal_with_tax = $subtotal + $default_shipping_cost;
        $total = CartAction::calculateCoupon($request, $subtotal_with_tax, $products);

        return view('frontend.cart.cart-partial', compact('all_cart_items', 'products', 'subtotal', 'default_shipping_cost', 'total', 'quantity_msg'));
    }

    public function removeCartItem(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'product_attributes' => 'nullable',
        ]);

        $product_attributes = isset($request->product_attributes) && is_array($request->product_attributes) ? $request->product_attributes : [];

        $attribute = $this->formatAttributes($product_attributes);

        CartHelper::remove($request->id, $attribute);

        // response view
        $default_shipping_cost = CartAction::getDefaultShippingCost();

        $all_cart_items = CartHelper::getItems();
        $products = Product::whereIn('id', array_keys($all_cart_items))->get();

        $subtotal = CartAction::getCartTotalAmount($all_cart_items, $products);
        $subtotal_with_tax = $subtotal + $default_shipping_cost;
        $total = CartAction::calculateCoupon($request, $subtotal_with_tax, $products);

        return view('frontend.cart.cart-partial', compact('all_cart_items', 'products', 'subtotal', 'default_shipping_cost', 'total'));
    }

    public function clearCart()
    {
        CartHelper::clear();

        return response()->json(FlashMsg::explain('success', __('Cart cleared')), 200);
    }

    public function cartPageApplyCouponAjax(Request $request)
    {
        $default_shipping_cost = CartAction::getDefaultShippingCost();

        $all_cart_items = CartHelper::getItems();
        $products = Product::whereIn('id', array_keys($all_cart_items))->get();

        $subtotal = CartAction::getCartTotalAmount($all_cart_items, $products);
        $total = $subtotal - CartAction::calculateCoupon($request, $subtotal, $products, 'DISCOUNT');

        $is_changed = $subtotal > $total;
        $status = $is_changed ? 'success' : 'fail';
        $msg = $is_changed
                ? __('Coupon applied')
                : __('Coupon invalid');

        return response()->json([
            'type' => $status,
            'msg' => $msg,
            'details' => view('frontend.cart.cart-partial', [
                'all_cart_items' => $all_cart_items,
                'products' => $products,
                'subtotal' => $subtotal,
                'total' => $total,
            ])->render(),
        ]);
    }

    /** ==============================================================================
     *                  Checkout Page
     * ============================================================================== */
    public function checkoutPageApplyCouponAjax(Request $request)
    {
        $all_cart_items = Cart::content();
        $products = Product::with('category', 'subCategory', 'childCategory')->whereIn('id', $all_cart_items?->pluck('id')?->toArray())->get();
        $subtotal = Cart::subtotal(); //CartAction::getCartTotalAmount($all_cart_items, $products);

        $coupon_amount = CartService::calculateCoupon($request, $subtotal, $products, 'DISCOUNT');
        // if coupon is valid ProductCoupon,
        // or is shipping coupon
        if ($coupon_amount) {
            return response()->json([
                'type' => 'success',
                'coupon_amount' => round($coupon_amount, 2),
            ]);
        } else {
            $shipping_option = ShippingMethodOption::where('coupon', $request->coupon)->first();

            if (! is_null($shipping_option)) {
                return response()->json([
                    'type' => 'success',
                    'coupon_amount' => 0,
                ]);
            }
        }

        return response()->json(['type' => 'danger', 'coupon_amount' => 0]);
    }

    public function calculateCheckout(Request $request)
    {
        $request->validate([
            'selected_shipping_option' => 'required|numeric',
            'country' => 'required|exists:countries,id',
            'state' => 'nullable|exists:states,id',
            'coupon' => 'nullable|string|max:191',
        ]);

        // subtotal
        $all_cart_items = CartHelper::getItems();
        $products = Product::whereIn('id', array_keys($all_cart_items))->get();
        $subtotal = CartAction::getCartTotalAmount($all_cart_items, $products);
        $coupon_amount = CartAction::calculateCoupon($request, $subtotal, $products, 'DISCOUNT');

        // if user selected a shipping option
        if (isset($request->selected_shipping_option)) {
            $shipping_is_valid = CartAction::validateSelectedShipping($request->selected_shipping_option, $request->coupon);

            if (! $shipping_is_valid) {
                $shipping_method = ShippingMethod::with('availableOptions')->find($request->selected_shipping_option);

                if (is_null($shipping_method)) {
                    return response()->json(FlashMsg::explain('danger', __('Please select valid shipping option')));
                }

                if (isset(optional($shipping_method)->availableOptions)) {
                    $minimum_order_amount = optional(optional($shipping_method)->availableOptions)->minimum_order_amount ?? 0;
                    $minimum_order_amount = float_amount_with_currency_symbol($minimum_order_amount);

                    $message = __('Minimum total order amount has to be')
                                .' '.$minimum_order_amount;

                    if (optional(optional($shipping_method)->availableOptions)->setting_preset === 'min_order_or_coupon') {
                        $message .= ' '.__('or a valid coupon has to be given.');
                    } elseif (optional(optional($shipping_method)->availableOptions)->setting_preset === 'min_order_and_coupon') {
                        $message .= ' '.__('and a valid coupon has to be given.');
                    }

                    return response()->json(FlashMsg::explain('danger', $message));
                }

                return response()->json(FlashMsg::explain('danger', __('Please select valid shipping option')));
            }

            $shipping_info = CartAction::getSelectedShippingCost($request->selected_shipping_option, $subtotal, $request->coupon);
            $selected_shipping_cost = $shipping_info['cost'];
            $shipment_tax_applicable = $shipping_info['is_taxable'];

            // check if shipping is taxable
            if ($shipment_tax_applicable) {
                // if shipment is taxable (is_taxable), calculate tax with shipping
                $subtotal_with_shipping = $subtotal + $selected_shipping_cost;
                $tax_amount = CartAction::getCheckoutTaxAmount($subtotal_with_shipping, $request->country, $request->state);
            } else {
                // else, only calculate subtotal
                $tax_amount = CartAction::getCheckoutTaxAmount($subtotal, $request->country, $request->state);
            }

            $total = $subtotal + $selected_shipping_cost + $tax_amount - $coupon_amount;

            return response()->json([
                'subtotal' => (string) round($subtotal, 2),
                'selected_shipping_cost' => (string) round($selected_shipping_cost, 2),
                'tax_amount' => (string) round($tax_amount, 2),
                'coupon_amount' => (string) round($coupon_amount, 2),
                'total' => (string) round($total, 2),
            ]);
        }
    }

    /** ==============================================================================
     *                  HELPER FUNCTIONS
     * ============================================================================== */
    public function formatAttributes($product_attributes)
    {
        $attribute = $product_attributes;
        if (isset($attribute['price'])) {
            $attribute['price'] = floatval($attribute['price']);
        }

        return $attribute;
    }
}
