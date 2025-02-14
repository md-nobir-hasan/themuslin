<?php

use Modules\ShippingModule\Http\Controllers\Api\VendorShippingMethodApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("vendor/v1")->group(function (){
    Route::group(['prefix' => 'auth/', 'middleware' => 'auth:sanctum'], function () {
        Route::prefix("shipping-method")->controller(VendorShippingMethodApiController::class)->as("shipping-method.")->group(function (){
            Route::get("/", "index")->name("index");
            Route::get("/create", "create")->name("create");
            Route::post("/store", "store")->name("store");
            Route::get("/delete/{id}", "destroy")->name("destroy");
            Route::get("/edit/{id}", "edit")->name("edit");
            Route::post("/update/{id}", "update")->name("update");
            Route::post("/make-default", "makeDefault")->name("make-default");
        });
    });
});