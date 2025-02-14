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

use Modules\PluginManage\Http\Controllers\PluginManageController;

Route::group(['middleware' => ['auth:admin','adminglobalVariable', 'setlang:backend'],'prefix' => 'admin-home'],function () {
    Route::get("plugin-manage/all",[PluginManageController::class,"index"])->name("admin.plugin.manage.all");
    Route::get("plugin-manage/new",[PluginManageController::class,"add_new"])->name("admin.plugin.manage.new");
    Route::post("plugin-manage/new",[PluginManageController::class,"store_plugin"]);
    Route::post("plugin-manage/delete",[PluginManageController::class,"delete_plugin"])->name("admin.plugin.manage.delete");
    Route::post("plugin-manage/status",[PluginManageController::class,"change_status"])->name("admin.plugin.manage.status.change");
});