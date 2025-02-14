<?php

use Modules\Order\Http\Controllers\Api\VendorOrderApiController;

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
        Route::prefix("orders")->as("vendor.orders.")->middleware("auth:sanctum")
        ->controller(VendorOrderApiController::class)->group(function (){
            Route::get("/", "index")->name("list");
            Route::get("/details/{id}", "details")->name("details");
        });
    });
});