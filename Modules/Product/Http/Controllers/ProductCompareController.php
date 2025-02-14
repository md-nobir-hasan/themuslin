<?php

namespace Modules\Product\Http\Controllers;

use App\Helpers\CompareHelper;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\Size;
use Modules\Campaign\Entities\CampaignSoldProduct;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;
use Throwable;

class ProductCompareController extends Controller
{
    public function addToCompare(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        return CompareHelper::add($request->product_id);
    }

    /**
     * @throws Throwable
     */
    public function add_to_compare(Request $request): JsonResponse
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
                'quantity_msg' => __('Requested amount can not be compare. Campaign product stock limit is over!'),
            ]);
        }

        //        try {
        $cart_data = $request->all();
        $product = Product::withAvg('reviews', 'rating')->withCount('reviews')->findOrFail($cart_data['product_id']);

        //            dd($product);
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
        if ($cart_data['product_variant']) {
            $size_name = Size::find($cart_data['selected_size'] ?? 0)->name ?? '';
            $color_name = Color::find($cart_data['selected_color'] ?? 0)->name ?? '';

            $product_detail = ProductInventoryDetail::where('id', $cart_data['product_variant'] ?? 0)->first();

            $product_attributes = $product_detail->attribute?->pluck('attribute_value', 'attribute_name', 'inventory_details')
                ->toArray();

            $options = [
                'variant_id' => $request->product_variant,
                'color_name' => $color_name,
                'size_name' => $size_name,
                'attributes' => $product_attributes,
                'description' => $product->description,
                'sku' => $product?->inventory?->sku,
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

        $options['slug'] = $product->slug;
        $options['campaign_product'] = $product->campaign_product;
        $options['campaign_sold_product'] = $product->campaign_sold_product;
        $options['sort_description'] = $product->summary;
        $options['review_count'] = $product->reviews_count;
        $options['avg_review'] = $product->reviews_avg_rating;

        //        Cart::instance("compare")->destroy();
        Cart::instance('compare')->add(['id' => $cart_data['product_id'], 'name' => $product->name, 'qty' => $cart_data['quantity'], 'price' => $final_sale_price, 'weight' => '0', 'options' => $options]);

        return response()->json([
            'type' => 'success',
            'msg' => 'Item added to compare.',
            'header_area' => view('frontend.partials.header.navbar.card-and-wishlist-area')->render(),
        ]);
        //        } catch (\Exception $exception) {
        //            return response()->json([
        //                'type' => 'error',
        //                'error_msg' => __('Something went wrong!')
        //            ]);
        //        }
    }

    public function removeFromCompare(Request $request)
    {
        $request->validate([
            'rowId' => 'required|string',
        ]);

        Cart::instance('compare')->remove($request->rowId);

        return response()->json([
            'success' => true,
            'msg' => __('Item removed from compare page'),
            'header_area' => view('frontend.partials.header.navbar.card-and-wishlist-area')->render(),
        ]);
    }

    public function clearCompare()
    {
        return CompareHelper::clear();
    }
}
