<?php

namespace App\Http\Controllers;

use App\Action\CartAction;
use App\AdminShopManage;
use App\Helpers\CartHelper;
use App\Helpers\FlashMsg;
use App\Helpers\WishlistHelper;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\Size;
use Modules\Campaign\Entities\CampaignSoldProduct;
use Modules\Order\Services\CheckoutCouponService;
use Modules\Product\Entities\InventoryDetailsAttribute;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;
use DB;

class FrontendCartController extends Controller
{


    public function addToCart(Request $request)
    {

        if (!auth()->check()) {
            return response()->json([
                'type' => 'error',
                'error_msg' => __('Please log in first!'),
                'msg' => __('Please log in first!'),
            ]);
        }

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
                'quantity_msg' => __('Available quantity is ' . (int)$product_inventory_details->stock_count),
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Available quantity is ' . (int)$product_inventory->stock_count),
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
            $product = Product::findOrFail($cart_data['product_id']);
            // with(['
            // taxOptions:tax_class_options.id,country_id,state_id,city_id,rate', 'vendorAddress:vendor_addresses.id,country_id,state_id,city_id'])
            // ->withSum('taxOptions', 'rate')
            // ->findOrFail($cart_data['product_id']);

            // if (! empty($product->vendor_id) && get_static_option('calculate_tax_based_on') == 'vendor_shop_address') {
            //     $vendorAddress = $product->vendorAddress;
            //     $product = tax_options_sum_rate($product, $vendorAddress->country_id, $vendorAddress->state_id, $vendorAddress->city_id);
            // } elseif (empty($product->vendor_id) && get_static_option('calculate_tax_based_on') == 'vendor_shop_address') {
            //     $vendorAddress = AdminShopManage::select('id', 'country_id', 'state_id', 'city as city_id')->first();

            //     $product = tax_options_sum_rate($product, $vendorAddress->country_id, $vendorAddress->state_id, $vendorAddress->city_id);
            // }

            $sale_price = $product->sale_price;
            $additional_price = 0;
            $extra_cost = 0;
            $regular_price = $product->price;

            if ($product->campaign_product) {
                $sale_price = $product?->campaign_product?->campaign_price;
            }

            if ($product_inventory_details) {
                $additional_price = $product_inventory_details->additional_price;
                $extra_cost = $product_inventory_details->add_cost;
            }

            $final_sale_price = $sale_price + $additional_price + $extra_cost;

            $product_image = $product->image_id;
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
            // $options['vendor_id'] = $product->vendor_id ?? null;
            $options['slug'] = $product->slug ?? null;
            $options['sku'] = $product->slug ?? null;
            $options['regular_price'] = $product->price ?? 0;
            // $options['tax_options_sum_rate'] = $product->tax_options_sum_rate ?? 0;




            // $product_inventory_details->stock_count
            if ($product_inventory_details) {

                $cartItem = Cart::instance('default')->content()->first(function ($item) use ($cart_data) {
                    return $item->id == $cart_data['product_id'] && $item->options['variant_id'] == $cart_data['product_variant'];
                });

                if ($cartItem) {
                    $currentQuantityInCart = $cartItem->qty;

                    // Prevent adding more if the quantity in the cart is equal to the stock count
                    if (($currentQuantityInCart + $cart_data['quantity'] > $product_inventory_details->stock_count)) {
                        return response()->json([
                            'type' => 'warning',
                            'quantity_msg' => __('Cannot add more than available stock'),
                        ]);
                    }
                }
            } else {
                // In the else case, only check by product ID
                $cartItem = Cart::instance('default')->content()->first(function ($item) use ($cart_data) {
                    return $item->id == $cart_data['product_id'];
                });

                if ($cartItem) {
                    $currentQuantityInCart = $cartItem->qty;
                    if (($currentQuantityInCart + $cart_data['quantity']) > $product_inventory->stock_count) {
                        return response()->json([
                            'type' => 'warning',
                            'quantity_msg' => __('Cannot add more than available stock'),
                        ]);
                    }
                }
            }

            $options['additional_price'] = $additional_price;
            $options['extra_cost'] = $extra_cost;

            // Proceed with adding the product to the cart
            Cart::instance('default')->add([
                'id' => $cart_data['product_id'],
                'name' => $product->name,
                'qty' => $cart_data['quantity'],
                'price' => $final_sale_price,
                'weight' => '0',
                'options' => $options,
            ]);

            return response()->json([
                'type' => 'success',
                'msg' => 'Item added to cart',
                // 'header_area' => view('frontend.partials.header.navbar.card-and-wishlist-area')->render(),
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

    public function addToWishList(Request $request)
    {

        if (!auth()->check()) {
            return response()->json([
                'type' => 'error',
                'error_msg' => __('Please log in first!'),
                'msg' => __('Please log in first!'),
            ]);
        }

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

        if (!Auth::guard('web')->check()) {
            return response()->json([
                'type' => 'warning',
                'msg' => __('If you want to add cart item then you have to login first.'),
            ]);
        }

        if ($product_inventory_details && $request->quantity > $product_inventory_details->stock_count) {
            return response()->json([
                'type' => 'warning',
                'msg' => __('Requested quantity is not available. Only available quantity is added to cart'),
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart'),
            ]);
        }

        if (!empty($product->campaign_product)) {
            $sold_count = CampaignSoldProduct::where('product_id', $request->product_id)->first();
            $product = Product::where('id', $request->product_id)->first();

            $product_left = $sold_count !== null ? $product->campaign_product->units_for_sale - $sold_count->sold_count : null;
        } else {
            $product_left = $product_inventory_details->stock_count ?? $product_inventory->stock_count;
        }

        // now we will check if product left is equal or bigger than quantity than we will check
        if (!($request->quantity <= $product_left) && $sold_count) {
            return response()->json([
                'type' => 'warning',
                'msg' => __('Requested amount can not be cart. Campaign product stock limit is over!'),
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
            if (!empty($cart_data['product_variant'] ?? null)) {
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

            $options['additional_price'] = $additional_price;
            
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
                'msg' => __('Something went wrong!'),
            ]);
        }
    }


    public function removeWishlistItem(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'type' => 'error',
                'error_msg' => __('Please log in first!'),
                'msg' => __('Please log in first!'),
            ]);
        }

        $request->validate([
            'rowId' => 'required|string',
        ]);

        Cart::instance('wishlist')->remove($request->rowId);

        $username = Auth::guard('web')->user()->id;

        Cart::instance('wishlist')->restore($username);



        return response()->json(FlashMsg::explain('success', __('Item removed from wishlist')), 200);
    }

    public function removeCartItem(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'type' => 'error',
                'error_msg' => __('Please log in first!'),
                'msg' => __('Please log in first!'),
            ]);
        }

        $request->validate([
            'rowId' => 'required|string',
        ]);

        Cart::instance('default')->remove($request->rowId);

        return response()->json(FlashMsg::explain('success', __('Item removed from cart')), 200);
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
                // 'header_area' => view('frontend.partials.header.navbar.card-and-wishlist-area')->render(),
            ])->setStatusCode(422);
        } else {
            return response()->json([
                'type' => 'success',
                'msg' => 'Cart is updated',
                // 'header_area' => view('frontend.partials.header.navbar.card-and-wishlist-area')->render(),
            ]);
        }
    }

    private static function cart_stock_out_error_msg($product)
    {
        return [__('Requested quantity is not available.')];
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
}
