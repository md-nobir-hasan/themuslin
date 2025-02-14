<?php

use Modules\MobileApp\Http\Controllers\Api\ApiOrderController;
use Modules\MobileApp\Http\Controllers\Api\V1\CategoryController;
use Modules\MobileApp\Http\Controllers\Api\V1\ChildCategoryController;
use Modules\MobileApp\Http\Controllers\Api\V1\CountryController;
use Modules\MobileApp\Http\Controllers\Api\V1\LanguageController;
use Modules\MobileApp\Http\Controllers\Api\V1\MobileSliderController;
use Modules\MobileApp\Http\Controllers\Api\V1\OrderApiController;
use Modules\MobileApp\Http\Controllers\Api\V1\SiteSettingsController;
use Modules\MobileApp\Http\Controllers\Api\V1\SubCategoryController;
use Modules\MobileApp\Http\Controllers\Api\V1\UserController;
use Modules\MobileApp\Http\Controllers\CampaignController;
use Modules\MobileApp\Http\Controllers\FeaturedProductController;
use Modules\MobileApp\Http\Controllers\MobileController;
use Modules\MobileApp\Http\Controllers\MobileIntroApiController;
use Modules\MobileApp\Http\Controllers\ProductController;

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

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('social/login', [UserController::class, 'socialLogin']);

Route::post('/send-otp-in-mail', [UserController::class, 'sendOTP']);
Route::post('/otp-success', [UserController::class, 'sendOTPSuccess']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);
Route::get('/check-username', [UserController::class, 'checkUsername']);

Route::get('/country', [CountryController::class, 'country']);
Route::get('/state/{country_id}', [CountryController::class, 'stateByCountryId']);
Route::get('/cities/{state_id}', [CountryController::class, 'cityByCountryId']);

Route::get('/get-countries', [CountryController::class, 'getCountries']);
Route::get('/get-states/{country_id}', [CountryController::class, 'getStateByCountryId']);
Route::get('/get-cities/{state_id}', [CountryController::class, 'getCityByCountryId']);
/*
 * todo:: all category route are below this line
 * */

/* category */
Route::group(['prefix' => 'category'], function () {
    Route::get('/', [CategoryController::class, 'allCategory']);
    Route::get('/{id}', [CategoryController::class, 'singleCategory']);
});
/* sub category */
Route::group(['prefix' => 'subcategory'], function () {
    Route::get('/{country_id}', [SubCategoryController::class, 'allSubCategory']);
    Route::get('/{country_id}/{id}', [SubCategoryController::class, 'singleSubCategory']);
});
/* sub category */
Route::group(['prefix' => 'child-category'], function () {
    Route::get('/{sub_category}', [ChildCategoryController::class, 'allChildCategory']);
    Route::get('/{sub_category}/{id}', [ChildCategoryController::class, 'singleChildCategory']);
});

Route::get("all-categories", [CategoryController::class, "allCategories"]);
/*
 * todo:: all type of category route ends
 * */

/*
 * todo:: all type of products route starts
 * */

// Product Route
// Fetch feature product
Route::get("featured/product", [FeaturedProductController::class, 'index']);
Route::get("campaign/product/{id?}", [FeaturedProductController::class, 'campaign']);
Route::get("campaign", [CampaignController::class, 'index']); // done
Route::get("product", [ProductController::class, 'search'])->name("api.products.search");
Route::get("product/{id}", [ProductController::class, 'productDetail']);
Route::get("product/price-range", [ProductController::class, 'priceRange']);
Route::get("search-items", [ProductController::class, 'searchItems']);
Route::get("terms-and-condition-page", [MobileController::class, 'termsAndCondition']);
Route::get("privacy-policy-page", [MobileController::class, 'privacyPolicy']);
Route::get("site_currency_symbol", [MobileController::class, 'site_currency_symbol']);
Route::get("language", [LanguageController::class, 'languageInfo']);
Route::get("mobile-slider/{type}", [MobileSliderController::class, "index"]);
Route::get("mobile-intro", [MobileIntroApiController::class, "mobileIntro"]);
Route::get("payment-gateway-list", [SiteSettingsController::class, "payment_gateway_list"]);
Route::post("calculate-tax-amount", [ApiOrderController::class,"calculateTaxAmount"]);


Route::post("product-review", [ProductController::class, 'storeReview']);
Route::post("category/{id}", [ProductController::class, 'singleProducts']);
Route::post("subcategory/{id}", [ProductController::class, 'singleProducts']);
Route::post("translate-string", [LanguageController::class, 'translateString'])->middleware("setlang:frontend");
Route::post("calculate-tax", [OrderApiController::class, "shippingMethods"]);
Route::post("checkout-contents", [OrderApiController::class,"checkoutContents"]);
Route::post("apply-coupon", [OrderApiController::class, "applyCoupon"]);

Route::post("checkout", [ApiOrderController::class, "placeOrder"]);
Route::post("update-payment", [ApiOrderController::class, "update_payment_status"]);

Route::group(['prefix' => 'user/', 'middleware' => 'auth:sanctum'], function () {
    Route::get("product/{id}", [ProductController::class, 'productDetail']);
    Route::post("logout", [UserController::class, 'logout']);
    Route::get("profile", [UserController::class, 'profile']);
    Route::post('update-subscribe-token', [UserController::class, 'updateFirebaseToken']);
    Route::get("delete-account", [UserController::class, 'deleteAccount']);

    Route::post("change-password", [UserController::class, 'changePassword']);
    Route::post("update-profile", [UserController::class, 'updateProfile']);
    Route::group(['prefix' => 'support-tickets'], function () {
        Route::post("/", [UserController::class, 'allTickets']);
        Route::post("/{id}", [UserController::class, 'viewTickets']);
    });

    /* Add shipping method */
    Route::get("all-shipping-address", [UserController::class, "get_all_shipping_address"]);
    Route::get("shipping-address/delete/{shipping}", [UserController::class, "delete_shipping_address"]);
    Route::post("add-shipping-address", [UserController::class, "storeShippingAddress"]);
    Route::get("get-department", [UserController::class, "get_department"]);
    Route::get("ticket", [UserController::class, "get_all_tickets"]);
    Route::get("ticket/{id}", [UserController::class, "single_ticket"]);
    Route::get("ticket/chat/{ticket_id}", [UserController::class, "fetch_support_chat"]);
    Route::post("ticket/chat/send/{ticket_id}", [UserController::class, "send_support_chat"]);
    Route::post("ticket/message-send", [UserController::class, 'sendMessage']);
    Route::post("ticket/create", [UserController::class, 'createTicket']);
    Route::post("ticket/priority-change", [UserController::class, 'priority_change']);
    Route::post("ticket/status-change", [UserController::class, 'status_change']);
    Route::get("order-list", [ApiOrderController::class,'orderList']);
    Route::get("order-detail/{id}", [ApiOrderController::class,'orderDetails']);
});