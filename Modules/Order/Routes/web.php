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

use http\Message;
use Illuminate\Http\Request;
use Modules\Order\Http\Controllers\AdminOrderController;
use Modules\Order\Http\Controllers\InvoiceController;
use Modules\Order\Http\Controllers\OrderController;
use Modules\Order\Http\Controllers\OrderTrackingFrontendController;
use Modules\Order\Http\Controllers\VendorOrderController;

$product_page_slug = getSlugFromReadingSetting('product_page') ?? 'product';

Route::middleware(['setlang:frontend', 'globalVariable', 'maintains_mode'])
    ->as("frontend.products.")
    ->prefix($product_page_slug)->group(function (){
        Route::prefix("track-order")->controller(OrderTrackingFrontendController::class)
        ->middleware(['setlang:frontend', 'globalVariable', 'maintains_mode'])->as("track.")
        ->group(function (){
            Route::get("/","trackOrderPage")->name("order");
        });
    });

Route::get("/order-tracking/{orderId}", [OrderController::class,"orderTracking"])->name('order.order-tracking');

Route::get('/checkout/coupon', [OrderController::class, 'sync_product_coupon'])->name('frontend.shop.checkout.sync-product-coupon.ajax');

Route::prefix("vendor-home/orders")->as("vendor.orders.")
    ->middleware("auth:vendor")->controller(VendorOrderController::class)->group(function (){
    Route::get("/", "index")->name("list");
    Route::get("/details/{id}", "details")->name("details");
    Route::post("/update-status/{subOrder?}", "updateOrderStatus")->name("update-order-status");
});

Route::prefix("admin-home/orders")->as("admin.orders.")
    ->middleware(["auth:admin","setlang:backend"])
    ->controller(AdminOrderController::class)->group(function (){
        Route::get("/", "orders")->name("list")->permission("orders");
        Route::post("/","orderSearch")->name("search")->permission("orders");
        Route::put("/update/order-track", "updateOrderTrack")->name("update.order-track")->permission("orders-update-order-track");
        Route::put("/update/order-status", "updateOrderStatus")->name("update.order-status")->permission("orders-update/order-status");
        Route::get("/sub-order", "index")->name("sub_order.list")->permission("orders-sub-order");
        Route::get("/vendor/list", "orderVendorList")->name("vendor.list")->permission("orders-vendor-list");
        Route::get("/vendor/{username}", "vendorOrders")->name("vendor.order")->permission("orders-vendor");
        Route::get("/details/{id}", "details")->name("order.details")->permission("orders-details");
        Route::get("/update/{id}", "edit")->name("edit")->permission("orders-update");
        Route::get("/sub-order-details/{id}", "subOrderDetails")->name("details")->permission("orders-sub-order-details");
        Route::get("generate/invoice/{orderId}",[InvoiceController::class, "generateInvoice"])->name("generate.invoice")->permission("orders-generate-invoice");
        Route::get("download/invoice/{orderId}",[InvoiceController::class, "downloadInvoice"])->name("download.invoice")->permission("orders-download-invoice");
        
});

Route::post('send-notification',function (Request $request){
    event(new Message($request->vendor_id, $request->order_data));
    return ['success' => true];
});