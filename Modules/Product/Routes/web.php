<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Modules\Product\Http\Controllers\CategoryController;
use Modules\Product\Http\Controllers\FrontendProductController;
use Modules\Product\Http\Controllers\OrderTrackingFrontendController;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Product\Http\Controllers\ProductSettingsController;
use Modules\Product\Http\Controllers\VendorProductController;
use Modules\Product\Http\Controllers\ProductCartController;

$product_page_slug = getSlugFromReadingSetting('product_page') ?? 'product';

Route::group(['prefix' => $product_page_slug, 'as' => 'frontend.products.',
    'middleware' => ['setlang:frontend', 'globalVariable', 'maintains_mode']
], function () use ($product_page_slug) {
    Route::get('/', 'FrontendProductController@products')->name('all');
    Route::get('search', 'FrontendProductController@search')->name('search');
    Route::get('/{slug}', [FrontendProductController::class,'productDetailsPage'])->name('single');
    Route::get('/quick-viewpage/{slug}', 'FrontendProductController@productQuickViewPage')->name('single-quick-view');

    Route::get('category/{slug}/{any?}', 'FrontendProductController@products_category')->name('category');
    Route::get('subcategory/{slug?}/{any?}', 'FrontendProductController@products_subcategory')->name('subcategory');
    Route::get('child-category/{slug?}/{any?}', 'FrontendProductController@products_child_category')->name('child-category');

    /**--------------------------------
     *          CART ROUTES
     * ---------------------------------*/
    Route::group(['prefix' => 'cart'], function () {
        Route::get('/all', 'FrontendProductController@cartPage')->name('cart');
        /**------------------------------------------------------------------------------------
        *          CART AJAX ROUTES
        *----------------------------------------------------------------------------------*/
        Route::post("move-to-wishlist", 'FrontendProductController@moveToWishlist')->name("cart.move.to.wishlist");

        Route::group(['prefix' => 'ajax'], function () {
            Route::controller(ProductCartController::class)->group(function (){
                Route::get("get-cart-items", "cart_items");
                Route::get("tax-amount", "taxAmount");
                Route::get("clear-all-cart-items", "clearCartItems");
                Route::post('remove', 'removeCartItem')->name('cart.ajax.remove');
                Route::get('details', 'cartStatus')->name('cart.status.ajax');
                Route::post('clear', 'clearCart')->name('cart.ajax.clear');
                Route::get('cart-info', 'getCartInfoAjax')->name('cart.info.ajax');
                Route::post('add-to-cart', 'add_to_cart')->name('add.to.cart.ajax');
                Route::post('update', 'cart_update_ajax')->name('cart.update.ajax');
                Route::post('coupon', 'applyCouponAjax')->name('cart.apply.coupon');
                Route::post("update-quantity", "updateQuantity");
            });
        });
    });
    /**--------------------------------
     *          WISHLIST ROUTES
     * ---------------------------------*/
    Route::group(['prefix' => 'wishlist'], function () {
        Route::post("move-to-cart", 'FrontendProductController@moveToCart')->name("wishlist.move.to.cart");

        Route::get('all', 'FrontendProductController@wishlistPage')->name('wishlist');
        Route::get('total', 'ProductWishlistController@getTotalItem')->name('wishlist.total'); // remove after details page
        Route::post('add', 'ProductWishlistController@addToWishlist')->name('add.to.wishlist');
        Route::post('ajax-add-to-wishlist', 'ProductWishlistController@addToWishlistAjax')->name('add.to.wishlist.ajax');
        Route::post('remove', 'ProductWishlistController@removeWishlistItem')->name('wishlist.ajax.remove');
        Route::post('clear', 'ProductWishlistController@clearWishlist')->name('wishlist.ajax.clear');
        Route::post('send-to-cart', 'ProductWishlistController@sendToCartAjax')->name('wishlist.send.to.cart');
        Route::post('send-to-cart-single', 'ProductWishlistController@sendSingleItemToCartAjax')->name('wishlist.send.to.cart.single');
        Route::get('wishlist-info', 'ProductWishlistController@getWishlistInfoAjax')->name('wishlist.info.ajax');
    });
    /**--------------------------------
     *      COMPARE PRODUCT ROUTES
     * ---------------------------------*/
    Route::group(['prefix' => 'compare'], function () {
        Route::get('all', 'FrontendProductController@productsComparePage')->name('compare');
        Route::post('add', 'ProductCompareController@add_to_compare')->name('add.to.compare');
        Route::post('remove', 'ProductCompareController@removeFromCompare')->name('compare.ajax.remove');
        Route::post('clear', 'ProductCompareController@clearCompare')->name('ajax.compare.update');
    });
});

Route::prefix('admin-home')->middleware(['setlang:backend', 'adminglobalVariable','auth:admin'])->group(function () {
    /*-----------------------------------
        COUPON ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'coupons', 'as' => 'admin.products.coupon.'], function () {
        Route::get('/', 'ProductCouponController@index')->name('all')->permission('coupons');
        Route::post('new', 'ProductCouponController@store')->name('new')->permission('coupons-new');
        Route::post('update', 'ProductCouponController@update')->name('update')->permission('coupons-update');
        Route::post('delete/{item}', 'ProductCouponController@destroy')->name('delete')->permission('coupons-delete');
        Route::post('bulk-action', 'ProductCouponController@bulk_action')->name('bulk.action')->permission('coupons-bulk-action');
        Route::get('check', 'ProductCouponController@check')->name('check')->permission('coupons-check');
        Route::get('get-products', 'ProductCouponController@allProductsAjax')->name('products')->permission('coupons-get-products');
    });

    /*==============================================
                    PRODUCT MODULE
    ==============================================*/
    Route::prefix('product')->as("admin.products.")->group(function (){
        Route::controller(ProductController::class)->group(function (){
            Route::get("create","create")->name("create")->permission("product-create");
            Route::post("create","store")->permission("product-create");
            Route::get("all","index")->name("all")->permission("product-all");
            Route::get("clone/{id}", "clone")->name("clone")->permission("product-clone");
            Route::post("status-update","update_status")->name("update.status")->permission("product-status-update");
            Route::get("update/{id}", "edit")->name("edit")->permission("product-update");
            Route::post("update-image", "updateImage")->name("update-image")->permission("product-update");
            Route::post("update/{id}", "update")->permission("product-update");
            Route::get("destroy/{id}", "destroy")->name("destroy")->permission("product-destroy");
            Route::post("bulk/destroy", "bulk_destroy")->name("bulk.destroy")->permission("product-bulk/destroy");

            Route::prefix('trash')->name('trash.')->group(function (){
                Route::get('/', 'trash')->name('all')->permission("product-trash");
                Route::get('/restore/{id}', 'restore')->name('restore')->permission("product-trash-restore");
                Route::get('/delete/{id}', 'trash_delete')->name('delete')->permission("product-trash-delete");
                Route::post("/bulk/destroy", "trash_bulk_destroy")->name("bulk.destroy")->permission("product-trash-bulk-destroy");
                Route::post("/empty", "trash_empty")->name("empty")->permission("product-trash-empty");
            });

            Route::get("search","productSearch")->name("search")->permission("product-trash-search");
        });

        Route::prefix("settings")->as("settings.")->group(function (){
            Route::controller(ProductSettingsController::class)->group(function (){
                Route::get("/", "index")->permission('product-settings');
                Route::post("/", "updateSettings")->permission('product-settings');
            });
        });
    });

    /*==============================================
                    Product Module Category Route
    ==============================================*/
    Route::prefix("category")->as("admin.product.category.")->group(function (){
        Route::controller(CategoryController::class)->group(function (){
            Route::post("category","getCategory")->name("all")->permission("category-category");
            Route::post("sub-category","getSubCategory")->name("sub-category")->permission("category-sub-category");
            Route::post("child-category","getChildCategory")->name("child-category")->permission("category-child-category");
        });
    });
});

Route::prefix('vendor-home')->middleware(['setlang:backend', 'adminglobalVariable','auth:vendor','userEmailVerify'])
->as("vendor.products.")->group(function () {
    /*==============================================
                    PRODUCT MODULE
    ==============================================*/
    Route::prefix('product')->group(function (){
        Route::controller(VendorProductController::class)->group(function (){
            Route::get("create","create")->name("create");
            Route::post("create","store");
            Route::get("all","index")->name("all");
            Route::get("clone/{id}", "clone")->name("clone");
            Route::post("status-update","update_status")->name("update.status");
            Route::get("update/{id}", "edit")->name("edit");
            Route::post("update/{id}", "update");
            Route::post("update-image", "updateImage")->name("update-image");
            Route::get("destroy/{id}", "destroy")->name("destroy");
            Route::post("bulk/destroy", "bulk_destroy")->name("bulk.destroy");

            Route::prefix('trash')->name('trash.')->group(function (){
                Route::get('/', 'trash')->name('all');
                Route::get('/restore/{id}', 'restore')->name('restore');
                Route::get('/delete/{id}', 'trash_delete')->name('delete');
                Route::post("/bulk/destroy", "trash_bulk_destroy")->name("bulk.destroy");
                Route::post("/empty", "trash_empty")->name("empty");
            });

            Route::get("search","productSearch")->name("search");
        });

    });
});

Route::prefix('vendor-home')->middleware(['setlang:backend', 'adminglobalVariable','auth:vendor','userEmailVerify'])->group(function (){
    /*==============================================
                    Product Module Category Route
    ==============================================*/
    Route::prefix("category")->as("vendor.product.category.")->group(function (){
        Route::controller(CategoryController::class)->group(function (){
            Route::post("category","getCategory")->name("all");
            Route::post("sub-category","getSubCategory")->name("sub-category");
            Route::post("child-category","getChildCategory")->name("child-category");
        });
    });
});
