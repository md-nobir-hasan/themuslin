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

use Modules\Campaign\Http\Controllers\CampaignController;
use Modules\Campaign\Http\Controllers\FrontendCampaignController;
use Modules\Campaign\Http\Controllers\VendorCampaignController;

Route::group(['as' => 'frontend.products.', 'middleware' => ['setlang:frontend', 'globalVariable', 'maintains_mode']],function() {
    /**--------------------------------
     *          CAMPAIGN ROUTES
     * ---------------------------------*/
    Route::get('campaign/{slug?}', [FrontendCampaignController::class, 'campaignPage'])->name('campaign');
});

/**--------------------------------------------------------------------------------------------------------------------------------
 *                          ADMIN PANEL ROUTES
 *----------------------------------------------------------------------------------------------------------------------------------*/
Route::prefix('admin-home')->middleware(['setlang:backend', 'adminglobalVariable','auth:admin'])->group(function () {

    /*------------------------------------------
        CAMPAIGN MODULES ADMIN PANEL
     ------------------------------------------*/
    Route::prefix('campaigns')->namespace('Campaign')->as('admin.campaigns.')->group(function () {
        Route::controller(CampaignController::class)->group(function () {
            Route::get('/', 'index')->name('all')->permission('campaigns');
            Route::get('new', 'create')->name('new')->permission('campaigns-new');
            Route::post('new', 'store')->permission('campaigns-new');
            Route::get('edit/{item}', 'edit')->name('edit')->permission('campaigns-edit');
            Route::post('update', 'update')->name('update')->permission('campaigns-update');
            Route::post('delete/{item}', 'destroy')->name('delete')->permission('campaigns-delete');
            Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission('campaigns-bulk-action');
            Route::post('delete-product', 'deleteProductSingle')->name('delete.product')->permission('campaigns-delete-product');
            Route::get('price', 'getProductPrice')->name('product.price')->permission('campaigns-price');
        });
    });
});

/**--------------------------------------------------------------------------------------------------------------------------------
 *                          VENDOR PANEL ROUTES
 *----------------------------------------------------------------------------------------------------------------------------------*/
Route::prefix('vendor-home')->middleware(['setlang:backend', 'adminglobalVariable'])->group(function () {
    /*------------------------------------------
        CAMPAIGN MODULES ADMIN PANEL
     ------------------------------------------*/
    Route::prefix('campaigns')->as('vendor.campaigns.')->group(function () {
        Route::controller(VendorCampaignController::class)->group(function () {
            Route::get('/', 'index')->name('all');
            Route::get('new', 'create')->name('new');
            Route::post('new', 'store');
            Route::get('edit/{item}', 'edit')->name('edit');
            Route::post('update', 'update')->name('update');
            Route::post('delete/{item}', 'destroy')->name('delete');
            Route::post('bulk-action', 'bulk_action')->name('bulk.action');
            Route::post('delete-product', 'deleteProductSingle')->name('delete.product');
            Route::get('price', 'getProductPrice')->name('product.price');
        });
    });
});