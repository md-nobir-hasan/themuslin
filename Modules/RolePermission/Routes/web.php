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

use Modules\RolePermission\Http\Controllers\AdminRoleManageController;

Route::prefix("admin-home")->middleware(['setlang:backend',"auth:admin"])->as("admin.")->group(function () {
    Route::resource('roles', AdminRoleManageController::class)
        ->only(["index","update","destroy","store"])
        ->middleware("role:Super Admin");
    // get all permission lists
    Route::get('roles/permissions/{id}', [AdminRoleManageController::class,"showPermissions"])
        ->name("roles.permissions")
        ->middleware("role:Super Admin");
    // update permission
    Route::post('roles/permissions/{id}', [AdminRoleManageController::class,"storePermissions"])
        ->middleware("role:Super Admin");
    // write store permission in laravel
    Route::post("/roles/permission/create", [AdminRoleManageController::class, "createPermission"])
        ->middleware("role:Super Admin")
        ->name("roles.create-permission");
});