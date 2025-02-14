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

use Modules\Badge\Http\Controllers\BadgeController;

Route::prefix('admin-home')->middleware(['setlang:backend', 'adminglobalVariable','auth:admin'])->group(function () {
    Route::group(['prefix' => 'badge', 'as' => 'admin.badge.'], function () {
        Route::controller(BadgeController::class)->group(function () {
            Route::get('/', 'index')->name('all')->permission('badge');
            Route::post('new', 'store')->name('store')->permission('badge-new');
            Route::post('update/{item}', 'update')->name('update')->permission('badge-update');
            Route::post('delete/{item}', 'destroy')->name('delete')->permission('badge-delete');
            Route::post('bulk-action', 'bulk_action_delete')->name('bulk.action.delete')->permission('badge-bulk-action');

            Route::prefix('trash')->group(function (){
                Route::get('/', 'trash')->name('trash')->permission('badge-trash');
                Route::get('/restore/{id}', 'trash_restore')->name('trash.restore')->permission('badge-trash-restore');
                Route::post('/delete/{item}', 'trash_delete')->name('trash.delete')->permission('badge-trash-delete');
                Route::post('/bulk-action', 'trash_bulk_action_delete')->name('trash.bulk.action.delete')->permission('badge-trash-bulk-action');
            });
        });
    });
});