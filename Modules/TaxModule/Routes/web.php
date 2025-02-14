<?php

use Illuminate\Support\Facades\Route;
use Modules\TaxModule\Http\Controllers\AdminTaxController;

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
/*-----------------------------------
            STATE TAX ROUTES
------------------------------------*/

Route::prefix('admin-home')->middleware(['setlang:backend', 'adminglobalVariable','auth:admin'])->group(function () {
    Route::prefix("tax-module")->as("admin.tax-module.")->group(function (){
        Route::get("settings", [AdminTaxController::class,"settings"])->name("settings")->permission("tax-module-settings");
        Route::put("settings", [AdminTaxController::class,"handleSettings"])->permission("tax-module-settings");
        // those are class route
        Route::get("tax-class", [AdminTaxController::class,"taxClass"])->name("tax-class")->permission("tax-module-tax-class");
        Route::post("tax-class", [AdminTaxController::class,"handlePostTaxClass"])->permission("tax-module-tax-class");
        Route::put("tax-class", [AdminTaxController::class,"handleTaxClass"])->permission("tax-module-tax-class");
        Route::delete("tax-class", [AdminTaxController::class,"deleteTaxClass"])->name('tax-class-delete')->permission("tax-module-tax-class");
        // those are class option route
        Route::get("tax-class-option/{id}", [AdminTaxController::class,"taxClassOption"])->name("tax-class-option")->permission("tax-module-tax-class-option");
        Route::post("tax-class-option/{id}", [AdminTaxController::class,"handleTaxClassOption"])->name("tax-class-option")->permission("tax-module-tax-class-option");


        Route::get('country-state', [AdminTaxController::class, 'getCountryStateInfo'])->name('country.state.info.ajax');
        Route::get('state-city', [AdminTaxController::class, 'getCountryCityInfo'])->name('state.city.info.ajax');
    });

    /*-----------------------------------
        TAX ROUTES
    ------------------------------------*/
    Route::prefix('tax')->group(function () {
        /*-----------------------------------
            COUNTRY TAX ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'country', 'as' => 'admin.tax.country.'], function () {
            Route::get('/', 'CountryTaxController@index')->name('all')->permission('tax-country');
            Route::post('new', 'CountryTaxController@store')->name('new')->permission('tax-country-new');
            Route::post('update', 'CountryTaxController@update')->name('update')->permission('tax-country-update');
            Route::post('delete/{item}', 'CountryTaxController@destroy')->name('delete')->permission('tax-country-delete');
            Route::post('bulk-action', 'CountryTaxController@bulk_action')->name('bulk.action')->permission('tax-country-bulk-action');
        });
        /*-----------------------------------
            STATE TAX ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'state', 'as' => 'admin.tax.state.'], function () {
            Route::controller("StateTaxController")->group(function (){
                Route::get('/', 'index')->name('all')->permission('tax-state');
                Route::post('new', 'store')->name('new')->permission('tax-state-new');
                Route::post('update', 'update')->name('update')->permission('tax-state-update');
                Route::post('delete/{item}', 'destroy')->name('delete')->permission('tax-state-delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission('tax-state-bulk-action');
            });
        });
    });
});