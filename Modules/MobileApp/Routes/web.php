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

/*------------------------------------------
        Mobile Slider
     ------------------------------------------*/

use Modules\MobileApp\Http\Controllers\AdminMobileController;
use Modules\MobileApp\Http\Controllers\MobileCampaignController;
use Modules\MobileApp\Http\Controllers\MobileFeaturedProductController;
use Modules\MobileApp\Http\Controllers\MobileIntrosController;
use Modules\MobileApp\Http\Controllers\MobileSliderController;
use Modules\MobileApp\Http\Controllers\VendorMobileIntrosController;


/**--------------------------------------------------------------------------------------------------------------------------------
 *                          ADMIN PANEL ROUTES
 *----------------------------------------------------------------------------------------------------------------------------------*/
Route::prefix('admin-home')->middleware(['setlang:backend', 'adminglobalVariable','auth:admin','role:Super Admin'])->group(function () {
    Route::prefix('mobile-slider')->as("admin.mobile.slider.")->group(function () {
        Route::controller(MobileSliderController::class)->group(function (){
            Route::get('/list', "index")->name('all');
            Route::get('new', "create")->name('create');
            Route::post('new', "store");
            Route::get('update/{mobileSlider}', "edit")->name("edit");
            Route::post('update/{mobileSlider}', "update");
            Route::post('delete/{mobileSlider}', "destroy")->name("delete");
        });
    });

    Route::prefix('mobile-intro')->as("admin.mobile.intro.")->group(function () {
        Route::controller(MobileIntrosController::class)->group(function (){
            Route::get('/list', "index")->name('all');
            Route::get('new', "create")->name('create');
            Route::post('new', "store");
            Route::get('update/{mobileIntro}', "edit")->name("edit");
            Route::post('update/{mobileIntro}', "update");
            Route::post('delete/{mobileIntro}', "destroy")->name("delete");
        });
    });

    Route::prefix('vendor-intro')->as("admin.mobile.vendor.intro.")->group(function () {
        Route::controller(VendorMobileIntrosController::class)->group(function (){
            Route::get('/list', "index")->name('all');
            Route::get('new', "create")->name('create');
            Route::post('new', "store");
            Route::get('update/{mobileIntro}', "edit")->name("edit");
            Route::post('update/{mobileIntro}', "update");
            Route::post('delete/{mobileIntro}', "destroy")->name("delete");
        });
    });

    Route::prefix('mobile-slider-two')->as("admin.mobile.slider.two.")->group(function () {
        Route::controller(MobileSliderController::class)->group(function (){
            Route::get('list', "two_index")->name('all');
            Route::get('new', "two_create")->name('create');
            Route::post('new', "two_store");
            Route::get('update/{mobileSlider}', "two_edit")->name("edit");
            Route::post('update/{mobileSlider}', "two_update");
            Route::post('delete/{mobileSlider}', "two_destroy")->name("delete");
        });
    });

    Route::prefix('mobile-slider-three')->as("admin.mobile.slider.three.")->group(function () {
        Route::controller(MobileSliderController::class)->group(function (){
            Route::get('list', "three_index")->name('all');
            Route::get('new', "three_create")->name('create');
            Route::post('new', "three_store");
            Route::get('update/{mobileSlider}', "three_edit")->name("edit");
            Route::post('update/{mobileSlider}', "three_update");
            Route::post('delete/{mobileSlider}', "three_destroy")->name("delete");
        });
    });

    Route::prefix('mobile-featured-product')->as("admin.featured.product.")->group(function () {
        Route::controller(MobileFeaturedProductController::class)->group(function (){
            Route::get('list', "index")->name('all');
            Route::get('new', "create")->name('create');
            Route::post('new', "store");
            Route::get('update/{id}', "edit")->name("edit");
            Route::post('update/{id}', "update");
            Route::post('delete/{id}', "destroy")->name("delete");
        });
    });

    Route::prefix('mobile-campaign')->as("admin.mobile.campaign.")->group(function () {
        Route::controller(MobileCampaignController::class)->group(function (){
            Route::get('create', "index")->name('create');
            Route::post('update', "update")->name("update");
        });
    });

    Route::prefix('mobile-settings')->as("admin.mobile.settings.")->group(function () {
        Route::controller(AdminMobileController::class)->group(function (){
            Route::get('terms-and-controller', "terms_and_condition")->name('terms_and_condition');
            Route::post('terms-and-controller', "update_terms_and_condition");
            Route::get('privacy-policy', "privacy_and_policy")->name('privacy.policy');
            Route::post('privacy-policy', "update_privacy_and_policy");
            Route::get('buyer-app-settings', "buyerAppSettings")->name('buyer-app-settings');
            Route::post('buyer-app-settings', "updateBuyerAppSettings");
        });
    });
});