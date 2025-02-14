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

use Illuminate\Support\Facades\Route;
use Modules\CountryManage\Http\Controllers\AdminUserController;
use Modules\CountryManage\Http\Controllers\CityController;

/**--------------------------------------------------------------------------------------------------------------------------------
 *                          ADMIN PANEL ROUTES
 *----------------------------------------------------------------------------------------------------------------------------------*/



Route::prefix('admin-home')->as("admin.")->middleware(['setlang:backend', 'adminglobalVariable','auth:admin'])->group(function () {
    /*-----------------------------------
        COUNTRY ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'country',"as" => "country."], function () {
        Route::controller("CountryManageController")->group(function (){
            Route::get('/', 'index')->name('all')->permission('country');
            Route::post('new', 'store')->name('new')->permission('country-new');
            Route::post('update', 'update')->name('update')->permission('country-update');
            Route::post('delete/{item}', 'destroy')->name('delete')->permission('country-delete');
            Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission('country-bulk-action');
        });
    });

    /*-----------------------------------
        STATE ROUTES
    ------------------------------------*/
    Route::group(['prefix' => 'state', 'as' => 'state.'], function () {
        Route::controller("StateController")->group(function (){
            Route::get('/', 'index')->name('all')->permission('state');
            Route::post('new', 'store')->name('new')->permission('state-new');
            Route::post('update', 'update')->name('update')->permission('state-update');
            Route::post('delete/{item}', 'destroy')->name('delete')->permission('state-delete');
            Route::post('bulk-action', 'bulk_action')->name('bulk.action')->permission('state-bulk-action');
            Route::get('country-state', 'StateController@getStateByCountry')->name('by.country')->permission('state-country-state');
            Route::post('mutliple-country-state', 'getMultipleStateByCountry')->name('by.multiple.country')->permission('state-mutliple-country-state');
        });
    });

    Route::group(['prefix'=>'city'],function(){
        Route::controller(CityController::class)->group(function () {
            Route::match(['get','post'],'/','all_city')->name('city.all')->permission('city');
            Route::post('edit-city/{id?}','edit_city')->name('city.edit')->permission('city-edit-city');
            Route::post('change-status/{id}','city_status')->name('city.status')->permission('city-change-status');
            Route::post('delete/{id}','delete_city')->name('city.delete')->permission('city-delete');
            Route::post('bulk-action', 'bulk_action_city')->name('city.delete.bulk.action')->permission('city-bulk-action');

            Route::get('paginate/data', 'pagination')->name('city.paginate.data')->permission('city-paginate');
            Route::get('search-city', 'search_city')->name('city.search')->permission('city-search-city');

            Route::get('csv/import','import_settings')->name('city.import.csv.settings')->permission('city-csv-import');
            Route::post('csv/import','update_import_settings')->name('city.import.csv.update.settings')->permission('city-csv-import');
            Route::post('csv/import/database','import_to_database_settings')->name('city.import.database')->permission('city-csv-import-database');
        });
    });
});

//todo public routes for user and admin
Route::controller(AdminUserController::class)->group(function(){
    Route::post('get-state','get_country_state')->name('au.state.all');
    Route::post('get-city','get_state_city')->name('au.city.all');
});