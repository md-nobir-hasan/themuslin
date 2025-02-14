<?php

namespace Modules\CountryManage\Http\Controllers\Product;

use App\Helpers\CartHelper;
use App\Helpers\FlashMsg;
use App\Helpers\WishlistHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductWishlistController extends Controller
{
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

    public function addToWishlistAjax(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_attributes' => 'nullable|array',
        ]);

        $attributes = (array) $request->product_attributes;
        $attributes['user_id'] = auth('web')->check() ? auth('web')->id() : null;

        WishlistHelper::add($request->product_id, 1, $attributes);

        return response()->json(FlashMsg::explain('success', __('Item added to wishlist')), 200);
    }

    public function removeWishlistItem(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'product_attributes' => 'required|array',
        ]);
        WishlistHelper::remove($request->id, $request->product_attributes);

        return response()->json(FlashMsg::explain('success', __('Item removed from wishlist')), 200);
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
                // code...
                CartHelper::add($item['id'], $item['quantity'], $item['attributes']);
            }
        }

        WishlistHelper::clear();

        return back()->with(FlashMsg::explain('success', __('All items are sent to cart')));
    }
}
