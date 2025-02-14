<?php

use Illuminate\Http\Request;
use Modules\Product\Http\Controllers\Api\VendorProductApiController;

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
        Route::prefix("products")->as("api.vendor.products.")->controller(VendorProductApiController::class)->group(function (){
            Route::get("/all", "index")->name("search");
            Route::get("/destroy/{id}", "destroy")->name("destroy");
            Route::post("/update-status", "updateStatus");
        });
    });
});