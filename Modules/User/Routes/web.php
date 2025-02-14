<?php

use Illuminate\Support\Facades\Route;
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

Route::prefix('admin-home')->middleware(['setlang:backend', 'adminglobalVariable','auth:admin'])->group(function () {
    Route::group(['prefix' => 'frontend'], function () {
        Route::controller("UserController")->group(function(){
            Route::get('/new-user', 'new_user')->name('admin.frontend.new.user')->permission("frontend-new-user");
            Route::post('/new-user', 'new_user_add')->permission("frontend-new-user");
            Route::post('/user-update', 'user_update')->name('admin.frontend.user.update')->permission("frontend-user-update");
            Route::post('/user-password-change', 'user_password_change')->name('admin.frontend.user.password.change')->permission("frontend-user-password-change");
            Route::post('/delete-user/{id}', 'new_user_delete')->name('admin.frontend.delete.user')->permission("frontend-delete-user");
            Route::get('/all-user', 'all_user')->name('admin.all.frontend.user')->permission("frontend-all-user");
            Route::post('/all-user/bulk-action', 'bulk_action')->name('admin.all.frontend.user.bulk.action')->permission("frontend-all-user-bulk-action");
            Route::post('/all-user/email-status', 'email_status')->name('admin.all.frontend.user.email.status')->permission("frontend-all-user-email-status");
        });
    });
});