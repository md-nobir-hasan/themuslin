<?php

use Modules\MobileApp\Http\Controllers\Api\V1\CategoryController;
use Modules\MobileApp\Http\Controllers\Api\V1\CountryController;
use Modules\MobileApp\Http\Controllers\Api\V1\LanguageController;
use Modules\MobileApp\Http\Controllers\Api\V1\UserController;
use Modules\MobileApp\Http\Controllers\MobileController;
use Modules\MobileApp\Http\Controllers\MobileIntroApiController;
use Modules\SupportTicket\Http\Controllers\Api\VendorSupportTicketApiController;
use Modules\Vendor\Http\Controllers\Api\VendorAuthController;
use Modules\Vendor\Http\Controllers\Api\VendorDashboardApiController;
use Modules\Vendor\Http\Controllers\Api\VendorMediaUploadApiController;
use Modules\Vendor\Http\Controllers\Api\VendorProfileApiController;
use Modules\Vendor\Http\Controllers\VendorWalletApiController;
use Modules\Vendor\Http\Controllers\VendorWalletGatewaySettingApiController;

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
    Route::get('/mobile-intro', [MobileIntroApiController::class, "vendorMobileIntro"]);
    Route::post('/login', [VendorAuthController::class, 'login']);
    Route::post('/register', [VendorAuthController::class, 'register']);
    Route::get('/country', [CountryController::class, 'country']);
    Route::get('/state/{country_id}', [CountryController::class, 'stateByCountryId']);
    Route::get('/cities/{state_id}', [CountryController::class, 'cityByCountryId']);
    Route::get("all-categories", [CategoryController::class, "allCategories"]);
    Route::get('site_currency_symbol', [MobileController::class, 'site_currency_symbol']);
    Route::get('/language', [LanguageController::class, 'languageInfo']);
    Route::post('/translate-string', [LanguageController::class, 'translateString']);
    Route::get('/business-type', [VendorAuthController::class, 'businessType']);
    Route::post('/send-otp', [VendorAuthController::class, 'sendOTP']);
    Route::post('/reset-password', [VendorAuthController::class, 'resetPassword']);

    Route::group(['prefix' => 'auth/', 'middleware' => 'auth:sanctum'], function () {
        Route::post('logout', [VendorAuthController::class, 'logout']);
        Route::post('delete-account', [VendorAuthController::class, 'deleteAccount']);

        Route::post('/send-otp-in-mail', [VendorAuthController::class, 'user_email_verify_index']);
        Route::post('/otp-success', [VendorAuthController::class, 'user_email_verify']);
        Route::post('/reset-password', [VendorAuthController::class, 'resetPassword']);

        Route::prefix("/wallet")->group(function (){
            Route::controller(VendorWalletApiController::class)->group(function () {
                Route::get("/", "index")->name("home");
                Route::get("withdraw", "withdraw")->name("withdraw");
                Route::post("withdraw", "handleWithdraw");
                Route::get("withdraw-request", "withdrawRequestPage");
            });

            Route::prefix("/gateway")->controller(VendorWalletGatewaySettingApiController::class)->group(function (){
                Route::get("/","index")->name("index");
                Route::post("/update","update")->name("update");
            });
        });

        Route::controller(VendorAuthController::class)->group(function (){
            Route::get("profile", "profile");
            Route::post('/profile-update', [VendorProfileApiController::class,'vendor_profile_update']);
        });

        Route::controller(VendorDashboardApiController::class)->group(function (){
            Route::get("dashboard", "index");
            Route::post('update-subscribe-token', 'updateFirebaseToken');
        });

        /**---------------------------------------------------------------------------------------------------------------------------
         *                                      MEDIA UPLOAD ROUTE
         * ----------------------------------------------------------------------------------------------------------------------------*/
        Route::group(['prefix' => 'media-upload'], function () {
            Route::controller(VendorMediaUploadApiController::class)->group(function (){
                Route::post('/', 'upload_media_file')->name('vendor.upload.media.file');
                Route::post('/all', 'all_upload_media_file')->name('vendor.upload.media.file.all');
                Route::post('/alt', 'alt_change_upload_media_file')->name('vendor.upload.media.file.alt.change');
                Route::post('/delete', 'delete_upload_media_file')->name('vendor.upload.media.file.delete');
                Route::post('/loadmore', 'get_image_for_loadmore')->name('vendor.upload.media.file.loadmore');
            });
        });

        // update firebase subscribes token in delivery_mans table
    });
});