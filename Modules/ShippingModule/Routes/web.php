<?php

/*-----------------------------------
          SHIPPING ROUTES
------------------------------------*/

use Modules\ShippingModule\Http\Controllers\AdminShippingMethodController;
use Modules\ShippingModule\Http\Controllers\FrontendShippingController;
use Modules\ShippingModule\Http\Controllers\ShippingMethodController;
use Modules\ShippingModule\Http\Controllers\VendorShippingMethodController;
use Modules\ShippingModule\Http\Controllers\ZoneController;


Route::prefix('admin-home')->as("admin.")
    ->middleware(['setlang:backend', 'adminglobalVariable','auth:admin'])->group(function () {
    Route::prefix("shipping-method")->controller(AdminShippingMethodController::class)->as("shipping-method.")->group(function (){
        Route::get("/", "index")->name("index")->permission("shipping-method");
        Route::get("/create", "create")->name("create")->permission("shipping-method-create");
        Route::post("/store", "store")->name("store")->permission("shipping-method-store");
        Route::get("/delete/{id}", "destroy")->name("destroy")->permission("shipping-method-delete");
        Route::get("/edit/{id}", "edit")->name("edit")->permission("shipping-method-edit");
        Route::post("/update/{id}", "update")->name("update")->permission("shipping-method-update");
        Route::post("/make-default", "makeDefault")->name("make-default")->permission("shipping-method-make-default");
    });
});

Route::prefix('admin-home/shipping')->as("admin.")->middleware(['setlang:backend', 'adminglobalVariable','auth:admin'])->group(function () {
    /*-----------------------------------
                ZONE ROUTES
    ------------------------------------*/
    Route::controller(ZoneController::class)->prefix('zone')->as('shipping.zone.')->group( function () {
        Route::get('/', 'index')->name('all')->permission("shipping-zone");
        Route::get('/create', 'create')->name('create')->permission("shipping-zone-create");
        Route::post('/store', 'store')->name('store')->permission("shipping-zone-store");
        Route::get('/edit/{id?}', 'edit')->name('edit')->permission("shipping-zone-edit");
        Route::post('/update/{id?}', 'update')->name('update')->permission("shipping-zone-update");
        Route::get('/delete/{id?}', 'destroy')->name('delete')->permission("shipping-zone-delete");
    });

    /*-----------------------------------
        METHOD ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'method', 'as' => 'shipping.method.'], function () {
        Route::controller(ShippingMethodController::class)->group(function (){
            Route::get('/', 'index')->name('all')->permission("shipping-method");
            Route::get('new', 'create')->name('new')->permission("shipping-method-new");
            Route::post('new', 'store')->permission("shipping-method-new");
            Route::get('edit/{item}', 'edit')->name('edit')->permission("shipping-method-edit");
            Route::post('update', 'update')->name('update')->permission("shipping-method-update");
            Route::post('delete/{item}', 'destroy')->name('delete')->permission("shipping-method-delete");
            Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission("shipping-method-bulk-action");
            Route::post('make-default', 'makeDefault')->name('make.default')->permission("shipping-method-make-default");
        });
    });
});

// all vendor user route
Route::prefix("vendor-home")->as("vendor.")->middleware(['userEmailVerify','setlang:backend','adminglobalVariable','auth:vendor'])->group(callback: function () {
    Route::prefix("shipping-method")->controller(VendorShippingMethodController::class)->as("shipping-method.")->group(function (){
        Route::get("/", "index")->name("index");
        Route::get("/create", "create")->name("create");
        Route::post("/store", "store")->name("store");
        Route::get("/delete/{id}", "destroy")->name("destroy");
        Route::get("/edit/{id}", "edit")->name("edit");
        Route::post("/update/{id}", "update")->name("update");
        Route::post("/make-default", "makeDefault")->name("make-default");
    });
});

Route::controller(FrontendShippingController::class)->prefix("frontend/shipping")->as("frontend.shipping.module.")->middleware(['setlang:frontend', 'globalVariable', 'maintains_mode'])->group(function () {
    Route::post("shipping-methods", "shippingMethods")->name("methods");
    Route::get("methods", "shippingMethods");
});