<?php

use Illuminate\Http\Request;
use Modules\Inventory\Http\Controllers\Api\VendorInventoryApiController;

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
        Route::prefix("inventory")->as("api.vendor.inventory.")->controller(VendorInventoryApiController::class)->group(function (){
            Route::get("list", "index")->name("search");
        });
    });
});