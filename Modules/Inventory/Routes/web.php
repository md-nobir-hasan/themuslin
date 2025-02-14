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


use Modules\Inventory\Http\Controllers\InventoryController;
use Modules\Inventory\Http\Controllers\VendorInventoryController;

Route::prefix('admin-home')->middleware(['setlang:backend', 'adminglobalVariable','auth:admin'])->group(function () {
    /*-----------------------------------
        INVENTORY ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'product-inventory', 'as' => 'admin.products.inventory.'], function () {
        Route::controller(InventoryController::class)->group(function (){
            Route::get('/', 'index')->name('all')->permission("product-inventory");
            Route::get('edit/{item}', 'edit')->name('edit')->permission("product-inventory-edit");
            Route::post('update', 'update')->name('update')->permission("product-inventory-update"); // [===== ??? =====]
            Route::post('delete/{id}', 'destroy')->name('delete')->permission("product-inventory-delete");
            Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission("product-inventory-bulk-action");
            Route::post('attribute-delete', 'removeProductInventory')->name('attribute.delete')->permission("product-inventory-attribute-delete");
            Route::post('details-attribute-delete', 'removeInventoryDetailsAttribute')->name('details.attribute.delete')->permission("product-inventory-details-attribute-delete");
        });
    });
});

Route::prefix('vendor-home')->middleware(['userEmailVerify','setlang:backend', 'adminglobalVariable','auth:vendor','userEmailVerify'])->group(function () {
    /*-----------------------------------
        INVENTORY ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'product-inventory', 'as' => 'vendor.products.inventory.'], function () {
        Route::controller(VendorInventoryController::class)->group(function (){
            Route::get('/', 'index')->name('all');
            Route::get('edit/{item}', 'edit')->name('edit');
            Route::post('update', 'update')->name('update'); // [===== ??? =====]
            Route::post('delete', 'destroy')->name('delete');
            Route::post('bulk-action', 'bulk_action')->name('bulk.action');
            Route::post('attribute-delete', 'removeProductInventory')->name('attribute.delete');
            Route::post('details-attribute-delete', 'removeInventoryDetailsAttribute')->name('details.attribute.delete');
        });
    });
});