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

use App\Http\Controllers\VendorNotificationController;
use Illuminate\Support\Facades\Auth;
use Modules\Vendor\Http\Controllers\FrontendVendorController;
use Modules\Vendor\Http\Controllers\VendorBackendController;
use Modules\Vendor\Http\Controllers\VendorController;
use Modules\Vendor\Http\Controllers\VendorLoginController;
use Modules\Vendor\Http\Controllers\VendorMediaUploadController;
use Modules\Vendor\Http\Controllers\VendorProfileController;

/*======================================
    add Admin panel vendor route
=====================================*/
Route::prefix('admin-home/vendor')->middleware(['setlang:backend','adminglobalVariable','auth:admin','userEmailVerify'])->group(callback: function () {
    Route::controller(VendorBackendController::class)->as("admin.vendor.")->group(function (){
        Route::get("index", "index")->name("all")->permission("vendor-index");
        Route::get("create", "create")->name("create")->permission("vendor-create");
        Route::post("details", "show")->name("show")->permission("vendor-details");
        Route::post("update-status", "update_status")->name("update-status")->permission("vendor-update-status");
        Route::get("edit/{vendor}", "edit")->name("edit")->permission("vendor-edit");
        Route::post("edit/{vendor}", "update")->permission("vendor-edit");
        Route::post("create", "store")->permission("vendor-create");
        Route::post("get-state","get_state")->name("get.state")->permission("vendor-get-state");
        Route::post("get-city","get_city")->name("get.city")->permission("vendor-get-city");
        Route::get("delete/{vendor}","destroy")->name("delete")->permission("vendor-delete");
        Route::get("settings", "settings")->name("settings")->permission("vendor-settings");
        Route::put("settings", "updateSettings")->permission("vendor-settings");
        Route::get("commission-settings", "commissionSettings")->name("commission-settings")->permission("vendor-commission-settings");
        Route::put("commission-settings", "updateCommissionSettings")->permission("vendor-commission-settings");
        Route::put("individual-commission-settings", "updateIndividualCommissionSettings")->name("individual-commission-settings")->permission("vendor-individual-commission-settings");
        Route::get("vendor-commission-information/{id?}", "getVendorCommissionInformation")->name("get-vendor-commission-information")->permission("vendor-vendor-commission-information");
    });
});

/*======================================
    Add frontend vendor route
=======================================*/
Route::prefix("vendor-home")->middleware(['userEmailVerify','setlang:backend','adminglobalVariable','auth:vendor'])->controller(VendorController::class)->group(callback: function (){
    //todo:: user email verify
    Route::get('email-verify', 'user_email_verify_index')->name('vendor.email.verify')->middleware(['setlang:frontend', 'globalVariable']);
    Route::post('email-verify', 'user_email_verify');

    Route::get('resend-verify-code', 'reset_user_email_verify_code')->name('vendor.resend.verify.mail')->middleware(['setlang:frontend', 'globalVariable']);


    Route::get("notification",[VendorNotificationController::class, "index"])->name("vendor.notifications");
});

Route::prefix("vendor-home")->middleware(['setlang:backend','adminglobalVariable','auth:vendor','userEmailVerify'])->group(callback: function (){
    Route::controller(VendorController::class)->group(function (){
        //todo:: admin index
        Route::get("dashboard","index")->name("vendor.home");
        Route::get('/dark-mode-toggle', 'AdminDashboardController@dark_mode_toggle')->name('vendor.dark.mode.toggle');
        Route::get('/logout', 'VendorLoginController@logout')->name('vendor.logout');
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     * VENDOR Settings ROUTE
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(VendorProfileController::class)->prefix("profile")->group(function (){
        //todo:: vendor Profile
        Route::get('/settings', 'vendor_settings')->name('vendor.profile.settings');
        Route::get('/settings', 'vendor_settings')->name('vendor.profile.preview');
        Route::get('/profile-update', 'vendor_profile')->name('vendor.profile.update');
        Route::post('/profile-update', 'vendor_profile_update');
        Route::get('/password-change', 'vendor_password')->name('vendor.password.change');
        Route::post('/password-change', 'vendor_password_change');
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     * MEDIA UPLOAD ROUTE
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'vendor/media-upload'], function () {
        Route::controller(VendorMediaUploadController::class)->group(function (){
            Route::post('/', 'upload_media_file')->name('vendor.upload.media.file');
            Route::post('/all', 'all_upload_media_file')->name('vendor.upload.media.file.all');
            Route::post('/alt', 'alt_change_upload_media_file')->name('vendor.upload.media.file.alt.change');
            Route::post('/delete', 'delete_upload_media_file')->name('vendor.upload.media.file.delete');
            Route::post('/loadmore', 'get_image_for_loadmore')->name('vendor.upload.media.file.loadmore');
        });
    });
});

Route::prefix("vendor")->middleware(['guest','guest:vendor' ,'setlang:frontend', 'globalVariable', 'maintains_mode'])->group(callback: function () {
    Route::controller(VendorLoginController::class)->group(function () {
        Route::get("login", "login")->name("vendor.login");
        Route::post("login", "vendor_login");
        Route::get("register", "register")->name("vendor.register");
        Route::post("vendor_registration", "vendor_registration")->name("vendor.vendor_registration");
    });
});

/*=====================================
    Add vendor panel route
======================================*/
Route::get("frontend/vendor", [FrontendVendorController::class, "searchVendor"])->name('frontend.vendor-search');