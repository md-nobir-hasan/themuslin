<?php

namespace Modules\Product\Http\Controllers;

use App\Helpers\CartHelper;
use App\Helpers\FlashMsg;
use App\Helpers\WishlistHelper;
use Auth;
use DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\Size;
use Modules\Campaign\Entities\CampaignSoldProduct;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;

use function __;
use function auth;
use function back;
use function response;

class ProductWishlistController extends Controller
{
    public function getWishlistInfoAjax(): array
    {
        $all_wishlist_items = WishlistHelper::getItems();
        $products = Product::whereIn('id', array_keys($all_wishlist_items))->get();

        return [
            'item_total' => WishlistHelper::getTotalItem(),
            'wishlist' => view('frontend.partials.mini-wishlist', compact('all_wishlist_items', 'products'))->render(),
        ];
    }

    public function getTotalItem()
    {
        return response()->json([
            'total' => WishlistHelper::getTotalItem(),
        ], 200);
    }

    public function addToWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_attributes' => 'nullable|array',
        ]);

        $attributes = (array) $request->product_attributes;
        $attributes['user_id'] = auth('web')->check() ? auth('web')->id() : null;

        WishlistHelper::add($request->product_id, 1, $attributes);

        return back()->with(FlashMsg::explain('success', __('Item added to wishlist')));
    }

    /**
     * @throws \Throwable
     */
    public function addToWishlistAjax(Request $request)
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
            $product = Product::findOrFail($cart_data['product_id']);

            $sale_price = $product->sale_price;
            $additional_price = 0;

            if ($product->campaign_product) {
                $sale_price = $product?->campaign_product?->campaign_price;
            }

            if ($product_inventory_details) {
                $additional_price = $product_inventory_details->additional_price;
            }

            $final_sale_price = $sale_price + $additional_price;

            $product_image = $product->image;
            if ($cart_data['pid_id']) {
                $size_name = Size::find($cart_data['selected_size'] ?? 0)->name ?? '';
                $color_name = Color::find($cart_data['selected_color'] ?? 0)->name ?? '';

                $product_detail = ProductInventoryDetail::where('id', $cart_data['pid_id'])->first();

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
            $options['slug'] = $product->slug;
            $username = Auth::guard('web')->user()->id;
            Cart::instance('wishlist')->restore($username);
            Cart::instance('wishlist')->add(['id' => $cart_data['product_id'], 'name' => $product->name, 'qty' => $cart_data['quantity'], 'price' => $final_sale_price, 'weight' => '0', 'options' => $options]);
            Cart::instance('wishlist')->store($username);

            DB::commit();

            return response()->json([
                'type' => 'success',
                'msg' => 'Item added to wishlist',
                'header_area' => view('frontend.partials.header.navbar.card-and-wishlist-area')->render(),
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'type' => 'error',
                'error_msg' => __('Something went wrong!'),
            ]);
        }
    }

    public function removeWishlistItem(Request $request)
    {
        $request->validate([
            'rowId' => 'required|string',
        ]);

        Cart::instance('wishlist')->remove($request->rowId);

        return response()->json(FlashMsg::explain('success', __('Item removed from wishlist')) + [
            'header_area' => view('frontend.partials.header.navbar.card-and-wishlist-area')->render(),
        ], 200);
    }

    public function clearWishlist(Request $request)
    {
        WishlistHelper::clear();

        return response()->json(FlashMsg::explain('success', __('Wishlist cleared')), 200);
    }

    public function sendToCartAjax(Request $request)
    {
        $wishlist_items = WishlistHelper::getItems();

        foreach ($wishlist_items as $wishlist_item) {
            foreach ($wishlist_item as $item) {
                CartHelper::add($item['id'], $item['quantity'], $item['attributes']);
            }
        }

        WishlistHelper::clear();

        return back()->with(FlashMsg::explain('success', __('All items are sent to cart')));
    }

    public function sendSingleItemToCartAjax(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'id' => 'required|string',
            'quantity' => 'required|numeric',
            'product_attributes' => 'nullable',
        ]);

        $attributes = $this->formatProductAttribute($request->product_attributes);
        WishlistHelper::remove($request->id, $attributes);
        CartHelper::add($request->id, $request->quantity, $request->product_attributes);

        return response()->json(FlashMsg::explain('success', __('Item sent to cart')), 200);
    }

    private function formatProductAttribute(array $attributes): array
    {
        if (isset($attributes['price'])) {
            $attributes['price'] = floatval($attributes['price']);
        }

        return $attributes;
    }
}
