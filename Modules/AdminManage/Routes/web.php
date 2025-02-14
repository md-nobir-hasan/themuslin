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



/**--------------------------------------------------------------------------------------------------------------------------------
 *                          ADMIN PANEL ROUTES
 *----------------------------------------------------------------------------------------------------------------------------------*/


Route::prefix('admin-home')->middleware(['setlang:backend', 'adminglobalVariable'])->group(function () {
    /**  ---------------------------------------------------------------------------------------------------------------------------
     *                      ADMIN USER ROLE MANAGE
     * --------------------------------------------------------------------------------------------------------------------------  --*/
    Route::group(['prefix' => 'admin'], function () {
        Route::controller("AdminManageController")->group(function (){
            Route::get('/all', 'all_user')->name('admin.all.user')->permission('admin-all');
            Route::get('/new-user', 'new_user')->name('admin.new.user')->permission('admin-new-user');
            Route::post('/new-user', 'new_user_add')->permission('admin-new-user');
            Route::get('/user-edit/{id}', 'user_edit')->name('admin.user.edit')->permission('admin-user-edit');
            Route::post('/user-update', 'user_update')->name('admin.user.update')->permission('admin-user-update');
            Route::post('/user-password-change', 'user_password_change')->name('admin.user.password.change')->permission('admin-user-password');
            Route::post('/delete-user/{id}', 'new_user_delete')->name('admin.delete.user')->permission('admin-delete-user');
            /**---------------------------
             * ALL ADMIN ROLE ROUTES
             * -----------------------------*/
            Route::get('/role', 'all_admin_role')->name('admin.all.admin.role')->permission('admin-role');
            Route::get('/role/new', 'new_admin_role_index')->name('admin.role.new')->permission('admin-role-new');
            Route::post('/role/new', 'store_new_admin_role')->permission('admin-role-new');
            Route::get('/role/edit/{id}', 'edit_admin_role')->name('admin.user.role.edit')->permission('admin-role-edit');
            Route::post('/role/update', 'update_admin_role')->name('admin.user.role.update')->permission('admin-role-update');
            Route::post('/role/delete/{id}', 'delete_admin_role')->name('admin.user.role.delete')->permission('admin-role-delete');
        });
    });
});
