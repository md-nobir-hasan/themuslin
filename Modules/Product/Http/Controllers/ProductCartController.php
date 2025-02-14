<?php

namespace Modules\Product\Http\Controllers;

use App\Action\CartAction;
use App\AdminShopManage;
use App\Helpers\CartHelper;
use App\Helpers\FlashMsg;
use App\Helpers\ProductRequestHelper;
use App\Shipping\ShippingMethod;
use App\Shipping\ShippingMethodOption;
use DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\Size;
use Modules\Campaign\Entities\CampaignSoldProduct;
use Modules\Product\Entities\InventoryDetailsAttribute;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;
use Throwable;

use function __;
use function optional;
use function response;
use function view;

class ProductCartController extends Controller
{
    public function cart_items()
    {
        return Cart::instance('default')->content();
    }

    public function taxAmount()
    {
        return [
            'tax' => get_static_option('default_shopping_tax'),
        ];
    }

    /**
     * Top-bar mini-cart info
     */
    public function getCartInfoAjax(): array
    {
        $all_cart_items = CartHelper::getItems();
        $cart_item_ids = array_keys($all_cart_items);
        $product_stock_attributes = InventoryDetailsAttribute::where('product_id', $cart_item_ids)->get();
        $products = Product::whereIn('id', $cart_item_ids)->get();
        $subtotal = CartAction::getCartTotalAmount($all_cart_items, $products);

        return [
            'item_total' => CartHelper::getTotalQuantity(),
            'cart' => view('frontend.partials.mini-cart', compact(
                'all_cart_items',
                'products',
                'subtotal',
                'product_stock_attributes'
            ))->render(),
        ];
    }

    /**
     * @throws Throwable
     */
    public function add_to_cart(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
            'pid_id' => 'nullable',
            'product_variant' => 'nullable',
            'selected_size' => 'nullable',
            'selected_color' => 'nullable',
        ]);

        $product = Product::where('id', $request->product_id)->first();

        // first find inside cart
        $findCart = Cart::instance("default")->search(function ($cartItem, $rowId) use ($request) {
            return $cartItem->id === $request->product_id;
        })?->first();

        // check product minimum and maximum quantity
        if (($request->quantity + ($findCart?->qty ?? 0)) < $product->min_purchase) {
            return response()->json([
                'type' => 'error',
                'msg' => __("This product is required minimum quantity of $product->min_purchase"),
            ]);
        }

        if (($request->quantity + ($findCart?->qty ?? 0)) > $product->max_purchase) {
            return response()->json([
                'type' => 'error',
                'msg' => __("This product is allowed you to add maximum quantity of $product->max_purchase"),
            ]);
        }

        $product_inventory = ProductInventory::where('product_id', $request->product_id)->first();
        if ($request->product_variant) {
            $product_inventory_details = ProductInventoryDetail::where('id', $request->product_variant)->first();
        } else {
            $product_inventory_details = null;
        }

        if ($product_inventory_details && $request->quantity > $product_inventory_details->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart'),
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart'),
            ]);
        }

        $sold_count = 0;
        if (! empty($product->campaign_product)) {
            $sold_count = CampaignSoldProduct::where('product_id', $request->product_id)->first();

            $product_left = $sold_count !== null ? $product->campaign_product->units_for_sale - $sold_count->sold_count : null;
        } else {
            $product_left = ($product_inventory_details->stock_count ?? 0) ?? ($product_inventory->stock_count ?? 0);
        }

        // first check campaign date is available or not if a campaign is running then add those conditions
        if ($product->campaign_product?->start_date <= now() && $product->campaign_product?->end_date >= now()) {
            // now we will check if a product left is equal or bigger than quantity than we will check
            if (! ($request->quantity <= $product_left) && $sold_count) {
                return response()->json([
                    'type' => 'warning',
                    'quantity_msg' => __('Requested amount can not be cart. Campaign product stock limit is over!'),
                ]);
            }
        }

        try {
            $cart_data = $request->all();
            $product = Product::with(['taxOptions:tax_class_options.id,country_id,state_id,city_id,rate', 'vendorAddress:vendor_addresses.id,country_id,state_id,city_id'])
                ->withSum('taxOptions', 'rate')
                ->findOrFail($cart_data['product_id']);

            if (! empty($product->vendor_id) && get_static_option('calculate_tax_based_on') == 'vendor_shop_address') {
                $vendorAddress = $product->vendorAddress;
                $product = tax_options_sum_rate($product, $vendorAddress->country_id, $vendorAddress->state_id, $vendorAddress->city_id);
            } elseif (empty($product->vendor_id) && get_static_option('calculate_tax_based_on') == 'vendor_shop_address') {
                $vendorAddress = AdminShopManage::select('id', 'country_id', 'state_id', 'city as city_id')->first();

                $product = tax_options_sum_rate($product, $vendorAddress->country_id, $vendorAddress->state_id, $vendorAddress->city_id);
            }

            $sale_price = $product->sale_price;
            $additional_price = 0;
            $regular_price = $product->price;

            if ($product->campaign_product) {
                $sale_price = $product?->campaign_product?->campaign_price;
            }

            if ($product_inventory_details) {
                $additional_price = $product_inventory_details->additional_price;
            }

            $final_sale_price = $sale_price + $additional_price;

            $product_image = $product->image_id;
            var_dump($product_image);
            exit();
            if (! empty($cart_data['product_variant'] ?? null)) {
                $size_name = Size::find($cart_data['selected_size'] ?? 0)->name ?? '';
                $color_name = Color::find($cart_data['selected_color'] ?? 0)->name ?? '';

                $product_detail = ProductInventoryDetail::where('id', $cart_data['product_variant'])->first();
                $product_attributes = $product_detail?->attribute?->pluck('attribute_value', 'attribute_name', 'inventory_details')
                    ->toArray();

                $options = [
                    'variant_id' => $request->product_variant,
                    'color_name' => $color_name,
                    'size_name' => $size_name,
                    'attributes' => $product_attributes,
                    'image' => $product_detail->attr_image ?? $product_image,
                ];
            } else {
                $options = ['image' => $product_image];
            }

            $category = $product?->category?->id;
            $subcategory = $product?->subCategory?->id ?? null;

            $options['used_categories'] = [
                'category' => $category,
                'subcategory' => $subcategory,
            ];
            $options['vendor_id'] = $product->vendor_id ?? null;
            $options['slug'] = $product->slug ?? null;
            $options['sku'] = $product->slug ?? null;
            $options['regular_price'] = $product->price ?? 0;
            $options['tax_options_sum_rate'] = $product->tax_options_sum_rate ?? 0;

            Cart::instance('default')->add(['id' => $cart_data['product_id'], 'name' => $product->name, 'qty' => $cart_data['quantity'], 'price' => $final_sale_price, 'weight' => '0', 'options' => $options]);

            return response()->json([
                'type' => 'success',
                'msg' => 'Item added to cart',
                'header_area' => view('frontend.partials.header.navbar.card-and-wishlist-area')->render(),
                'cart-content' => Cart::instance('default')->content(),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'type' => 'error',
                'error_msg' => __('Something went wrong!'),
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @throws Throwable
     */
    public function add_to_wishlist(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
            'pid_id' => 'nullable',
            'product_variant' => 'nullable',
            'selected_size' => 'nullable',
            'selected_color' => 'nullable',
        ]);

        $product_inventory = ProductInventory::where('product_id', $request->product_id)->first();
        if ($request->product_variant) {
            $product_inventory_details = ProductInventoryDetail::where('id', $request->product_variant)->first();
        } else {
            $product_inventory_details = null;
        }

        if (! Auth::guard('web')->check()) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('If you want to add cart item into wishlist than you have to login first.'),
            ]);
        }

        if ($product_inventory_details && $request->quantity > $product_inventory_details->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart'),
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart'),
            ]);
        }

        if (! empty($product->campaign_product)) {
            $sold_count = CampaignSoldProduct::where('product_id', $request->product_id)->first();
            $product = Product::where('id', $request->product_id)->first();

            $product_left = $sold_count !== null ? $product->campaign_product->units_for_sale - $sold_count->sold_count : null;
        } else {
            $product_left = $product_inventory_details->stock_count ?? $product_inventory->stock_count;
        }

        // now we will check if product left is equal or bigger than quantity than we will check
        if (! ($request->quantity <= $product_left) && $sold_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested amount can not be cart. Campaign product stock limit is over!'),
            ]);
        }

        DB::beginTransaction();
        try {
            $cart_data = $request->all();
            $product = Product::withSum('taxOptions', 'rate')->findOrFail($cart_data['product_id']);

            $sale_price = $product->sale_price;
            $additional_price = 0;

            if ($product->campaign_product) {
                $sale_price = $product?->campaign_product?->campaign_price;
            }

            if ($product_inventory_details) {
                $additional_price = $product_inventory_details->additional_price;
            }

            $final_sale_price = $sale_price + $additional_price;

            $product_image = $product->image_id;
            if ($cart_data['product_variant']) {
                $size_name = Size::find($cart_data['selected_size'])->name ?? '';
                $color_name = Color::find($cart_data['selected_color'])->name ?? '';

                $product_detail = ProductInventoryDetail::where('id', $cart_data['product_variant'])->first();

                $product_attributes = $product_detail->attribute?->pluck('attribute_value', 'attribute_name', 'inventory_details')
                    ->toArray();

                $options = [
                    'variant_id' => $request->product_variant,
                    'color_name' => $color_name,
                    'size_name' => $size_name,
                    'attributes' => $product_attributes,
                    'image' => $product_detail->image ?? $product_image,
                ];
            } else {
                $options = ['image' => $product_image];
            }

            $category = $product?->category?->id;
            $subcategory = $product?->subCategory?->id ?? null;

            $options['used_categories'] = [
                'category' => $category,
                'subcategory' => $subcategory,
            ];
            $options['regular_price'] = $product->price ?? 0;
            $options['tax_options_sum_rate'] = $product->tax_options_sum_rate ?? 0;
            $username = Auth::guard('web')->user()->id;

            Cart::instance('wishlist')->restore($username);
            Cart::instance('wishlist')->add(['id' => $cart_data['product_id'], 'name' => $product->name, 'qty' => $cart_data['quantity'], 'price' => $final_sale_price, 'weight' => '0', 'options' => $options]);
            Cart::instance('wishlist')->store($username);

            DB::commit();

            return response()->json([
                'type' => 'success',
                'msg' => 'Item added to wishlist',
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'type' => 'error',
                'error_msg' => __('Something went wrong!'),
            ]);
        }
    }

    public function cartStatus(): JsonResponse
    {
        return response()->json([
            'total_price' => CartHelper::getAttributeTotal('price'),
            'all_items' => CartHelper::getItems(),
        ], 200);
    }

    private static function cart_stock_out_error_msg($product)
    {
        return [__('Requested quantity is not available for this <b>'.$product->name.'</b> product')];
    }

    private function check_inventory_to_add_cart_item($productId, $variantId, $quantity)
    {
        $product_inventory = ProductInventory::where('product_id', $productId)->first();

        if (! empty($variantId)) {
            $product_inventory_details = ProductInventoryDetail::where('id', $variantId)->first();

            if ($product_inventory_details && $quantity > $product_inventory_details->stock_count) {
                return false;
            }
        }

        if ($product_inventory && $quantity > $product_inventory->stock_count) {
            return false;
        }

        return true;
    }

    public function cart_update_ajax(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
            'variant_id' => 'nullable',
        ]);

        $products = Product::whereIn("id", Cart::instance('default')->content()?->pluck("id")?->toArray() ?? [])->get();

        $error = [];
        for ($i = 0; $i < count($request->product_id ?? []); $i++) {
            // get cart item from the cart package
            $product_data = Cart::instance('default')->get($data['product_id'][$i]);

            $product = $products->where("id", $product_data->id ?? 0)->first();
            // check product minimum and maximum quantity
            if ($data['quantity'][$i] < $product->min_purchase) {
                return response()->json([
                    'type' => 'error',
                    'msg' => __("This product is required minimum quantity of $product->min_purchase"),
                ]);
            }

            if ($data['quantity'][$i] > $product->max_purchase) {
                return response()->json([
                    'type' => 'error',
                    'msg' => __("This product is allowed you to add maximum quantity of $product->max_purchase"),
                ]);
            }
            // now check inventory for updating cart
            if ($this->check_inventory_to_add_cart_item($product_data->id, $data['variant_id'][$i], $data['quantity'][$i])) {
                Cart::instance('default')->update($data['product_id'][$i], ['qty' => $data['quantity'][$i]]);
            } else {
                $error[] = self::cart_stock_out_error_msg($product_data);
            }
        }

        if (! empty($error)) {
            return response()->json(['error_type' => 'cart'] + [
                'type' => 'warning',
                'error_messages' => $error,
                'header_area' => view('frontend.partials.header.navbar.card-and-wishlist-area')->render(),
                ])->setStatusCode(422);
        } else {
            return response()->json([
                'type' => 'success',
                'msg' => 'Cart is updated',
                'header_area' => view('frontend.partials.header.navbar.card-and-wishlist-area')->render(),
            ]);
        }
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'cart_data' => 'required|array',
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

                $requested_quantity = $campaign['remaining_quantity'];
            } else {
                // handle non-campaign quantity
                $update_available_quantity = $available_quantity - $requested_quantity;

                if ($update_available_quantity > 0) {
                    CartHelper::update(
                        $cart_item['id'],
                        $requested_quantity,
                        $product_attribute
                    );
                }
            }

            $update_available_quantity = $available_quantity - $requested_quantity;

            // if item quantity is not available in stock show message
            if ($update_available_quantity <= 0) {
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

        $cart_item_ids = array_keys($all_cart_items);
        $product_stock_attributes = InventoryDetailsAttribute::where('product_id', $cart_item_ids)->get();

        return view('frontend.cart.cart-partial', compact(
            'all_cart_items',
            'products',
            'subtotal',
            'default_shipping_cost',
            'total',
            'quantity_msg',
            'product_stock_attributes'
        ));
    }

    public function removeCartItem(Request $request)
    {
        $request->validate([
            'rowId' => 'required|string',
        ]);

        Cart::instance('default')->remove($request->rowId);

        return response()->json(FlashMsg::explain('success', __('Item removed from cart')) + [
            'header_area' => view('frontend.partials.header.navbar.card-and-wishlist-area')->render(),
        ], 200);
    }

    public function clearCart(): JsonResponse
    {
        CartHelper::clear();

        return response()->json(FlashMsg::explain('success', __('Cart cleared')), 200);
    }

    public function cartPageApplyCouponAjax(Request $request): JsonResponse
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
        $all_cart_items = CartHelper::getItems();
        $products = Product::whereIn('id', array_keys($all_cart_items))->get();
        $subtotal = CartAction::getCartTotalAmount($all_cart_items, $products);

        $coupon_amount = CartAction::calculateCoupon($request, $subtotal, $products, 'DISCOUNT');

        // if coupon is valid ProductCoupon,
        // or is shipping coupon
        if ($coupon_amount) {
            return response()->json([
                'type' => 'success',
                'coupon_amount' => round($coupon_amount, 0),
            ]);
        } else {
            $shipping_option = ShippingMethodOption::where('coupon', $request->coupon)->first();

            if (! is_null($shipping_option)) {
                return response()->json([
                    'type' => 'failed',
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
                $shipping_method = ShippingMethod::with('availableOptions')->find($request->selected_shipping_option); // $request->selected_shipping_option;

                if (is_null($shipping_method)) {
                    return response()->json(FlashMsg::explain('danger', __('Please select valid shipping option')));
                }

                if (isset(optional($shipping_method)->availableOptions)) {
                    $minimum_order_amount = optional(optional($shipping_method)->availableOptions)->minimum_order_amount ?? 0;
                    $minimum_order_amount = float_amount_with_currency_symbol($minimum_order_amount);
                    $setting_preset = optional(optional($shipping_method)->availableOptions)->setting_preset;
                    $message = ProductRequestHelper::getShippingOptionMessage($setting_preset, $minimum_order_amount);

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
                'subtotal' => (string) round($subtotal, 0),
                'selected_shipping_cost' => (string) round($selected_shipping_cost, 0),
                'tax_amount' => (string) round($tax_amount, 0),
                'coupon_amount' => (string) round($coupon_amount, 0),
                'total' => (string) round($total, 0),
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

    public function clearCartItems()
    {
        Cart::instance('default')->destroy();

        return response()->json(FlashMsg::explain('success', __('Cart cleared')), 200);
    }

    public function updateQuantity(Request $request)
    {
        Cart::instance('default')->update($request->rowId, $request->qty);

        return response()->json([
            'msg' => __('Updated'),
        ]);
    }
}
