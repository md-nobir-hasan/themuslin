<?php

use Illuminate\Http\Request;
use Modules\Campaign\Http\Controllers\Api\VendorCampaignApiController;

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
        Route::prefix("campaign")->controller(VendorCampaignApiController::class)->group(function (){
            Route::get('all', 'index');
            Route::get('/{id}', 'details');
            Route::post('update-status', 'updateStatus');
        });
    });
});