<?php


use Modules\Order\Http\Controllers\AdminOrderController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryMenuController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AdminShopManageController;
use App\Http\Controllers\Admin\MediaUploadController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\FrontendShippingAddresssController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\XgNotificationController;
use Illuminate\Support\Facades\Route;
use Modules\CountryManage\Http\Controllers\Product\ProductCartController;
use Modules\Order\Http\Controllers\OrderController;
use Modules\Vendor\Http\Controllers\FrontendVendorController;
use App\Http\Controllers\FrontendUserController;
use App\Http\Controllers\FrontendCartController;
use App\Http\Controllers\DcastaliaOrderController;
use App\Http\Controllers\DHLShippingController;
use App\Http\Controllers\InternationalShippingController;

Route::get('update-notification', XgNotificationController::class)
    ->middleware(['setlang:frontend','setlang:backend'])->name('update-notification');

/**===========================================================================================================================
*                          FRONTEND
* ==========================================================================================================================*/
Route::group(['middleware' => ['setlang:frontend', 'globalVariable', 'maintains_mode']], function () {

  
    /*----------------------------------------
      FRONTEND: CUSTOM FORM BUILDER ROUTES
    -----------------------------------------*/
    Route::post('submit-custom-form', 'FrontendFormController@custom_form_builder_message')->name('frontend.form.builder.custom.submit');

    $blog_page_slug = getSlugFromReadingSetting('blog_page','blog');

    /**---------------------------------------------------------------------------------------------------------------------------
     *                          FRONTEND ROUTES
     * --------------------------------------------------------------------------------------------------------------------------*/

    /**------------------ Muslin ----------------------*/

    Route::get('/', 'FrontendController@index')->name('homepage');
    Route::get('about', 'FrontendController@about')->name('about');
    Route::get('contact', 'FrontendController@contact')->name('contact');
    Route::post('contact-submit', 'FrontendController@contactSubmit')->name('contact.submit');
    Route::get('faqs', 'FrontendController@faq')->name('faq');
    Route::get('return-policy', 'FrontendController@returnPolicy')->name('return.policy');
    Route::get('privacy-policy', 'FrontendController@privacyPolicy')->name('privacy.policy');
    Route::get('terms-condition', 'FrontendController@termsCondition')->name('terms.condition');
    Route::get('blogs', 'FrontendController@blogs')->name('blogs');
    Route::get('blogs/{slug}', 'FrontendController@blogDetail')->name('blog.detail');
    Route::get('product/{slug}', 'FrontendController@category')->name('category');
    Route::get('campaign/{slug}', 'FrontendController@campaignDetails')->name('campaign');
    Route::get('product-detail/{slug}', 'FrontendController@product')->name('product.details');
    Route::post('home-search', 'FrontendController@homeSearch')->name('home.search');
    Route::post('product-search', 'FrontendController@productSearch')->name('product.search');
    
    Route::post('category-info', 'FrontendController@categoryInfo')->name('category.info');

    //for apply filter on product list view
    Route::post('category-info-backend', 'FrontendController@categoryInfoBackend')->name('category.info.backend');


    Route::get('/sign-up', 'FrontendUserController@showRegistrationForm')->name('registration');
    Route::post('submit-phone-verification', 'FrontendUserController@createCustomerPhoneVerification')->name('submit-phone-verification');
    Route::post('check-phone-verification', 'FrontendUserController@checkCustomerPhoneVerification')->name('check-phone-verification');

    Route::get('send-sms','FrontendUserController@sendSms')->name('send-sms');

    Route::get('account-setup','FrontendUserController@showAfterVerificationForm')->name('account-setup');
    Route::post('submit-registration','FrontendUserController@submitRegistrationForm')->name('submit-registration');

    Route::get('sign-in','FrontendUserController@showLoginForm')->name('sign-in');
    Route::post('submit-login','FrontendUserController@showLoginFormSubmit')->name('submit-login');

    Route::get('forget-password','FrontendUserController@showForgetPasswordForm')->name('forget-password');
    Route::post('forget-password-submit','FrontendUserController@forgetPasswordSubmit')->name('forget-password-submit');
    Route::post('forget-password-otp-verification','FrontendUserController@forgetPasswordOtpVerification')->name('forget-password-otp-verify');

    Route::get('reset-password','FrontendUserController@showResetPassword')->name('reset-password');
    Route::post('reset-password-submit','FrontendUserController@resetPasswordSubmit')->name('reset-password-submit');

    Route::get('my-profile','FrontendUserController@showMyProfile')->name('my-profile');
    Route::post('my-profile-update','FrontendUserController@profileUpdate')->name('my-profile-update');
    Route::post('change-password','FrontendUserController@changePassword')->name('change-password');

    Route::post('add-address','FrontendUserController@addAddress')->name('add-address');
    Route::get('address-info/{id}','FrontendUserController@addressInfo')->name('address-info');
    Route::post('edit-address/{id}','FrontendUserController@editAddress')->name('edit-address');
    Route::get('delete-address/{id}','FrontendUserController@deleteAddress')->name('delete-address');
    Route::get('order-details/{id}','FrontendUserController@orderDetails')->name('order-details');

    Route::get('sign-out','FrontendUserController@submitSignOut')->name('sign-out');

    Route::get('/home/{id}', 'FrontendController@home_page_change')->name('homepage.demo');

    Route::get('cart-checkout', 'FrontendController@cartCheckoutPage')->name('frontend.cart-checkout');

    Route::post('dc-checkout', [DcastaliaOrderController::class, 'checkout'])->name('dc.checkout');

    Route::get('get-discount', [FrontendCartController::class, 'sync_product_coupon'])->name('dc.apply-coupon');


    Route::post('payment-success', [DcastaliaOrderController::class, 'paymentSuccess'])->name('dc.payment-success');
    Route::post('payment-fail', [DcastaliaOrderController::class, 'paymentFail'])->name('dc.payment-fail');
    Route::post('payment-cancel', [DcastaliaOrderController::class, 'paymentCancel'])->name('dc.payment-cancel');



    /*
     * Cart , Buy Now, Checkout Start
     */

    Route::post('add-to-wish','FrontendCartController@addToWishList')->name('product.add-to-wish');
    Route::post('remove-from-wish','FrontendCartController@removeWishlistItem')->name('product.remove-from-wish');

    Route::post('add-to-cart','FrontendCartController@addToCart')->name('product.add-to-cart');
    Route::get('my-cart','FrontendCartController@cartItems')->name('product.cart-items');
    Route::post('remove-from-cart','FrontendCartController@removeCartItem')->name('product.remove-from-cart');
    Route::post('update-cart','FrontendCartController@cart_update_ajax')->name('product.update-cart');

    Route::post('buy-now','FrontendCartController@addToCart')->name('product.buy-now');


    /*
     * Cart , Buy Now, Checkout End
     */



    // Newsletter
    // Newsletter
    Route::get('/subscriber/email-verify/{token}', 'FrontendController@subscriber_verify')->name('subscriber.verify');
    // Contact Route
    Route::post('/contact-message', 'FrontendFormController@send_contact_message')->name('frontend.contact.message');
    // Tax Info
    Route::get('country-info', 'FrontendController@getCountryInfo')->name('country.info.ajax');
    Route::get('get-country-info', 'FrontendController@getCountryStateInfo')->name('country.state.info.ajax');
    Route::get('get-state-info', 'FrontendController@getCountryCityInfo')->name('state.city.info.ajax');
    Route::get('state-info', 'FrontendController@getStateInfo')->name('state.info.ajax');
    // different shipping route
    Route::post('new-shipping', 'FrontendController@addUserShippingAddress')->name('frontend.add.user.shipping.address');
    // change site currency symbol
    Route::post('change-currency', 'FrontendController@changeSiteCurrency')->name('frontend.change.currency');
    Route::post('change-language', 'FrontendController@changeSiteLanguage')->name('frontend.change.language');

    /**--------------------------------
     * FRONT PAGE FILTER ROUTES
     * ---------------------------------*/
    Route::match(["get","post"],'filter-top-rated', 'FrontendController@topRatedProducts')->name('frontend.products.filter.top.rated');
    Route::match(["get","post"],'filter-top-selling', 'FrontendController@topSellingProducts')->name('frontend.products.filter.top.selling');
    Route::match(["get","post"],'filter-new', 'FrontendController@newProducts')->name('frontend.products.filter.new');
    Route::post('filter-campaign', 'FrontendController@campaignProduct')->name('frontend.products.filter.campaign');
    Route::post('filter-discount', 'FrontendController@discountedProduct')->name('frontend.products.filter.discounted');
    Route::get('filter-category', 'FrontendController@filterCategoryProducts')->name('frontend.products.filter.category');

    Route::get('attribute-data', 'FrontendController@getProductAttributeHtml')->name('frontend.products.attribute.html');

    /**--------------------------------
     * LANDING PAGES
     * ---------------------------------*/
    Route::prefix('land')->group(function () {
        /**--------------------------------
         * PRODUCT SHOP PAGES
         * ---------------------------------*/
        Route::prefix('home')->group(function () {
            Route::get('01', 'LandingController@homeOne');
            Route::get('02', 'LandingController@homeTwo');
        });

        /**--------------------------------
         * PRODUCT SHOP PAGES
         * ---------------------------------*/
        Route::prefix('shop')->group(function () {
            Route::get('grid', 'LandingController@shopGrid')->name('land.shop.grid');
            Route::get('list', 'LandingController@shopList')->name('land.shop.list');
            Route::get('right-sidebar', 'LandingController@shopRightSidebar')->name('land.shop.sidebar.right');
            Route::get('left-sidebar', 'LandingController@shopLeftSidebar')->name('land.shop.sidebar.left');
        });

        /**--------------------------------
         * BLOG PAGES
         * ---------------------------------*/
        Route::prefix('blog')->group(function () {
            Route::get('grid', 'LandingController@blogGrid');
            Route::get('list', 'LandingController@blogList');
            Route::get('news-update', 'LandingController@blogNewsUpdate');
            Route::get('details', 'LandingController@blogDetails');
        });
    });

    /**--------------------------------
     * CHECKOUT ROUTES
     * ---------------------------------*/
    Route::get('checkout', 'FrontendController@checkoutPage')->name('frontend.checkout');
    Route::get('get-tax-based-on-billing-address', 'FrontendController@cartItemsBasedOnBillingAddress')->name('frontend.get-tax-based-on-billing-address');
    Route::get('vendors', [FrontendVendorController::class,"index"])->name('frontend.vendors');
    Route::get('vendors/product/{slug}', [FrontendVendorController::class,"vendorProducts"])->name('frontend.vendors.single');
    Route::get('vendors/{slug}', [FrontendVendorController::class,"vendor"])->name('frontend.vendors.single');
    Route::post('checkout', [OrderController::class,"checkout"]);

    Route::get('checkout/apply/coupon', [ProductCartController::class,'checkoutPageApplyCouponAjax'])->name('frontend.checkout.apply.coupon');
    Route::get('checkout/calculate', 'ProductCartController@calculateCheckout')->name('frontend.checkout.calculate');
    Route::get('get-states/{country_id?}', [FrontendController::class,'getStates'])->name("frontend.get-states");

    Route::prefix("shipping-address")->as("frontend.shipping.address.")
        ->controller(FrontendShippingAddresssController::class)->group(function (){
            Route::post("/add", "store")->name("store");
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     *                   BLOG AREA FRONTEND ROUTES
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::get('/' . $blog_page_slug . '/{slug}', 'FrontendController@blog_single_page')->name('frontend.blog.single');
    Route::get('/' . $blog_page_slug . '-search', 'FrontendController@blog_search_page')->name('frontend.blog.search');
    Route::get('/' . $blog_page_slug . '-category/{id}/{any?}', 'FrontendController@category_wise_blog_page')->name('frontend.blog.category');
    Route::get('/' . $blog_page_slug . '-tags/{name}', 'FrontendController@tags_wise_blog_page')->name('frontend.blog.tags.page');

    /*----------------------------------
        FRONTEND: SUPPORT TICKET ROUTES
    ----------------------------------*/
    Route::group(['namespace' => 'Support'], function () {
        $support_ticket_page_slug = 'support'; // get_static_option('support_ticket_page_slug') ?? 'support';
        Route::get($support_ticket_page_slug, 'UserSupportTicketController@page')->name('frontend.support.ticket');
        Route::post($support_ticket_page_slug . '/new', 'UserSupportTicketController@store')->name('frontend.support.ticket.store');
    });


    /**---------------------------------------------------------------------------------------------------------------------------
     *                   USER DASHBOARD
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::get('campaign/user', 'FrontendController@user_campaign')->name('frontend.campaign.user');

    Route::prefix('user-home')->middleware(['userEmailVerify', 'setlang:frontend', 'globalVariable', 'maintains_mode'])->group(function () {
        Route::get('/', 'UserDashboardController@user_index')->name('user.home');
        Route::get('/download/file/{id}', 'UserDashboardController@download_file')->name('user.dashboard.download.file');

        Route::get('/change-password', 'UserDashboardController@change_password')->name('user.home.change.password');
        Route::get('/edit-profile', 'UserDashboardController@edit_profile')->name('user.home.edit.profile');
        Route::post('/profile-update', 'UserDashboardController@user_profile_update')->name('user.profile.update');
        Route::post('/password-change', 'UserDashboardController@user_password_change')->name('user.password.change');
        Route::get('/support-tickets', 'UserDashboardController@support_tickets')->name('user.home.support.tickets');

        Route::get('support-ticket/view/{id}', 'UserDashboardController@support_ticket_view')->name('user.dashboard.support.ticket.view');
        Route::post('support-ticket/priority-change', 'UserDashboardController@support_ticket_priority_change')->name('user.dashboard.support.ticket.priority.change');
        Route::post('support-ticket/status-change', 'UserDashboardController@support_ticket_status_change')->name('user.dashboard.support.ticket.status.change');
        Route::post('support-ticket/message', 'UserDashboardController@support_ticket_message')->name('user.dashboard.support.ticket.message');

        /**------------------------------------
         * Campaign log withdraw
         * -------------------------------------*/
        Route::get('/campaign/log/withdraw', 'UserDashboardController@campaign_log_withdraw')->name('user.campaign.log.withdraw');
        Route::post('/campaign/withdraw/submit', 'UserDashboardController@campaign_withdraw_submit')->name('user.campaign.withdraw.submit');
        Route::post('/campaign/withdraw/check', 'UserDashboardController@campaign_withdraw_check')->name('user.campaign.withdraw.check');
        Route::get('/campaign/withdraw/view/{id}', 'UserDashboardController@campaign_withdraw_view')->name('user.campaign.withdraw.view');

        /**------------------------------------
         * User Product Order
         * -------------------------------------*/
        Route::prefix('orders')->name('user.product.order.')->group(function () {
            Route::get('all', 'UserDashboardController@allOrdersPage')->name('all');
            Route::get('/refund/{item}', 'UserDashboardController@orderRefundPage')->name('refund');
            Route::post('/refund/{item}', 'UserDashboardController@handleRefundRequest');
            Route::get('/{item}', 'UserDashboardController@orderDetailsPage')->name('details');
            Route::post('/delivery-man-ratting/{item}', 'UserDashboardController@orderDeliveryManRatting')->name('delivery-man-ratting');
        });

        Route::get("refund-request",'UserDashboardController@allRefundsPage')->name("user.product.refund-request");
        Route::get("refund-request/{id}",'UserDashboardController@viewRequest')->name("user.product.refund-request.view");

        /**------------------------------------
         * User Shipping Address
         * -------------------------------------*/
        Route::get('shipping-address', 'UserDashboardController@allShippingAddress')->name('user.shipping.address.all');
        Route::get('shipping-address/new', 'UserDashboardController@createShippingAddress')->name('user.shipping.address.new');
        Route::post('shipping-address/new', 'UserDashboardController@storeShippingAddress');
        Route::post('shipping-address/delete/{id}', 'UserDashboardController@deleteShippingAddress')->name('shipping.address.delete');


        /**---------------------------------------------------------------------------------------------------------------------------
         * MEDIA UPLOAD ROUTE
         * ----------------------------------------------------------------------------------------------------------------------------*/
        Route::group(['prefix' => 'media-upload', 'namespace' => 'User'], function () {
            Route::post('/', 'MediaUploadController@upload_media_file')->name('user.upload.media.file');
            Route::post('/all', 'MediaUploadController@all_upload_media_file')->name('user.upload.media.file.all');
            Route::post('/alt', 'MediaUploadController@alt_change_upload_media_file')->name('user.upload.media.file.alt.change');
            Route::post('/delete', 'MediaUploadController@delete_upload_media_file')->name('user.upload.media.file.delete');
            Route::post('/loadmore', 'MediaUploadController@get_image_for_loadmore')->name('user.upload.media.file.loadmore');
        });

        /**---------------------------------------------------------------------------------------------------------------------------
         * MEDIA UPLOAD ROUTE
         * ----------------------------------------------------------------------------------------------------------------------------*/
        Route::group(['prefix' => 'media-upload', 'namespace' => 'Admin'], function () {
            Route::post('/', 'MediaUploadController@upload_media_file')->name('user.upload.media.file');
            Route::post('/all', 'MediaUploadController@all_upload_media_file')->name('user.upload.media.file.all');
            Route::post('/alt', 'MediaUploadController@alt_change_upload_media_file')->name('user.upload.media.file.alt.change');
            Route::post('/delete', 'MediaUploadController@delete_upload_media_file')->name('user.upload.media.file.delete');
            Route::post('/loadmore', 'MediaUploadController@get_image_for_loadmore')->name('user.upload.media.file.loadmore');
        });
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     * USER LOGIN - REGISTRATION
     * ----------------------------------------------------------------------------------------------------------------------------*/
    //user login
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('user.login');
    Route::post('/ajax-login', 'FrontendController@ajax_login')->name('user.ajax.login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('/login/forget-password', 'FrontendController@showUserForgetPasswordForm')->name('user.forget.password');
    Route::post('/login/forget-password', 'FrontendController@sendUserForgetPasswordMail');

    Route::get('/login/reset-password/{user}/{token}', 'FrontendController@showUserResetPasswordForm')->name('user.reset.password');
    Route::post('/login/reset-password', 'FrontendController@UserResetPassword')->name('user.reset.password.change');

    Route::post('/logout', 'Auth\LoginController@logout')->name('user.logout');
    Route::get('/user-logout', 'FrontendController@user_logout')->name('frontend.user.logout');
    //user register
    Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('user.register');
    Route::post('/register', 'Auth\RegisterController@register');
    //user email verify
    Route::get('/user/email-verify', 'UserDashboardController@user_email_verify_index')->name('user.email.verify');
    Route::post('/user/email-verify', 'UserDashboardController@user_email_verify');

    Route::get('/user/resend-verify-code', 'UserDashboardController@reset_user_email_verify_code')->name('user.resend.verify.mail');
    Route::post('/package-user/generate-invoice', 'FrontendController@generate_package_invoice')->name('frontend.package.invoice.generate');
});


Route::group(['middleware' => 'globalVariable'], function () {
    /**---------------------------------------------------------------------------------------------------------------------------
     *                          LANGUAGE CHANGE
     *----------------------------------------------------------------------------------------------------------------------------*/
    Route::get('/lang', 'FrontendController@lang_change')->name('frontend.langchange');
    Route::post('/subscribe-newsletter', 'FrontendController@subscribe_newsletter')->name('frontend.subscribe.newsletter');
    /**---------------------------------------------------------------------------------------------------------------------------
     *                          ADMIN LOGIN
     *----------------------------------------------------------------------------------------------------------------------------*/
    Route::middleware(['setlang:backend'])->group(function () {
        Route::get('/admin', 'Auth\LoginController@showAdminLoginForm')->name('admin.login');
        Route::get('/admin/forget-password', 'FrontendController@showAdminForgetPasswordForm')->name('admin.forget.password');
        Route::get('/admin/reset-password/{user}/{token}', 'FrontendController@showAdminResetPasswordForm')->name('admin.reset.password');
        Route::post('/admin/reset-password', 'FrontendController@AdminResetPassword')->name('admin.reset.password.change');
        Route::post('/admin/forget-password', 'FrontendController@sendAdminForgetPasswordMail');
        Route::get('/logout/admin', 'Admin\AdminDashboardController@adminLogout')->name('admin.logout');
        Route::post('/admin', 'Auth\LoginController@adminLogin');
    });
});

/**--------------------------------------------------------------------------------------------------------------------------------
 *                          ADMIN PANEL ROUTES
 *----------------------------------------------------------------------------------------------------------------------------------*/
Route::prefix('admin-home')->middleware(['setlang:backend', 'adminglobalVariable','auth:admin'])->group(function () {
    // international shipping
    Route::prefix('international-shipping')->group(function () {
        Route::get('/', 'Admin\InternationalShippingController@index')->name('admin.shipping.international.update-settings');
        Route::get('/test', 'Admin\InternationalShippingController@toggleStatus')->name('admin.shipping.international.toggle-status');
        Route::get('/credentials', 'Admin\InternationalShippingController@credentials')->name('admin.shipping.international.get-credentials');
        Route::post('/credentials', 'Admin\InternationalShippingController@updateCredentials')->name('admin.shipping.international.update-credentials');
    });
    
    // 404-page manage
    Route::get('404-page-manage', 'Admin\Error404PageManage@error_404_page_settings')->name('admin.404.page.settings')->permission('404-page-manage');
    Route::post('404-page-manage', 'Admin\Error404PageManage@update_error_404_page_settings')->permission('404-page-manage');

    // maintains page
    Route::get('/maintains-page/settings', 'Admin\MaintainsPageController@maintains_page_settings')->name('admin.maintains.page.settings')->permission('maintains-page-settings');
    Route::post('/maintains-page/settings', 'Admin\MaintainsPageController@update_maintains_page_settings')->permission('maintains-page-settings');

    Route::get("shipping-charge-settings",[SiteSettingsController::class, "shippingMethods"])->name("admin.shipping-charge-settings")->permission('shipping-charge-settings');
    Route::post("shipping-charge-settings",[SiteSettingsController::class, "updateShippingMethods"])->permission('shipping-charge-settings');

    Route::get("notification",[AdminNotificationController::class, "index"])->name("admin.notifications");

    /*-----------------------------------
        Admin Shop Manage Routes
    ------------------------------------*/
    Route::controller(AdminShopManageController::class)->group(function (){
       Route::get("invoice-note", "invoiceNote")->name("admin.shop-manage.invoice-note")->permission("invoice-note");
       Route::post("invoice-note", "saveInvoiceNote")->permission("invoice-note");
       Route::get("shop-manage", "index")->name("admin.shop-manage.update")->permission('shop-manage');
       Route::post("shop-manage", "update")->permission('shop-manage');
    });

    Route::controller(ReportController::class)->group(function(){
        Route::get('stock','stock')->name('report.stock');
        Route::post('stock-filter','stockFilter')->name('report.stock.filter');
        Route::post('stock-report','stockReport')->name('stock.report');
        

        Route::get('sales','sales')->name('report.sales');
        Route::post('sales-filter','salesFilter')->name('report.sales.filter');
        Route::post('sales-report','salesReport')->name('sales.report');

        Route::get('customer','customer')->name('report.customer');
        Route::post('customer-filter','customerFilter')->name('report.customer.filter');
        Route::post('customer-report','customerReport')->name('customer.report');
    });
   

    /*-----------------------------------
        MODULE SETTING ROUTES   
    ------------------------------------*/
    Route::group(['prefix' => 'page-settings', 'as' => 'admin.page.settings.'], function () {
        // wishlist
        Route::get('wishlist', 'Admin\ModulePageSettingsController@wishlistPageSettings')->name('wishlist')->permission('page-settings-wishlist');
        Route::post('wishlist', 'Admin\ModulePageSettingsController@storeWishlistPageSettings')->permission('page-settings-wishlist');
        // cart
        Route::get('cart', 'Admin\ModulePageSettingsController@cartPageSettings')->name('cart')->permission('page-settings-cart');
        Route::post('cart', 'Admin\ModulePageSettingsController@storeCartPageSettings')->permission('page-settings-cart');
        // checkout
        Route::get('checkout', 'Admin\ModulePageSettingsController@checkoutPageSettings')->name('checkout')->permission('page-settings-checkout');
        Route::post('checkout', 'Admin\ModulePageSettingsController@storeCheckoutPageSettings')->permission('page-settings-checkout');
        // compare
        Route::get('compare', 'Admin\ModulePageSettingsController@comparePageSettings')->name('compare')->permission('page-settings-compare');
        Route::post('compare', 'Admin\ModulePageSettingsController@storeComparePageSettings')->permission('page-settings-compare');
        // login/register
        Route::get('login-register', 'Admin\ModulePageSettingsController@userAuthPageSettings')->name('user.auth')->permission('page-settings-login-register');
        Route::post('login-register', 'Admin\ModulePageSettingsController@storeUserAuthPageSettings')->permission('page-settings-login-register');
        // shop page
        Route::get('shop-page', 'Admin\ModulePageSettingsController@shopPage')->name('shop.page')->permission('page-settings-shop-page');
        Route::post('shop-page', 'Admin\ModulePageSettingsController@storeShopPage')->permission('page-settings-shop-page');
        // product details page
        Route::get('product-details-page', 'Admin\ModulePageSettingsController@productDetailPage')->name('product.detail.page')->permission('page-settings-product-details-page');
        Route::post('product-details-page', 'Admin\ModulePageSettingsController@storeProductDetailPage')->permission('page-settings-product-details-page');
    });

    //HOME PAGE MANAGE
    Route::group(['prefix' => 'home-page-01', 'namespace' => 'Admin'], function () {
        Route::get('/latest-news', 'HomePageController@home_01_latest_news')->name('admin.homeone.latest.news')->permission('home-page-01-latest-news');
        Route::post('/latest-news', 'HomePageController@home_01_update_latest_news')->permission('home-page-01-latest-news');

        Route::get('/latest-event', 'HomePageController@home_01_latest_event')->name('admin.homeone.latest.event')->permission('home-page-01-latest-event');
        Route::post('/latest-event', 'HomePageController@home_01_update_latest_event')->permission('home-page-01-latest-event');

        Route::get('/feature-area', 'HomePageController@home_01_feature_area')->name('admin.homeone.feature.area')->permission('home-page-01-feature-area');
        Route::post('/feature-area', 'HomePageController@home_01_update_feature_area')->permission('home-page-01-feature-area');

        Route::get('/about-us', 'HomePageController@home_01_about_us')->name('admin.homeone.about.us')->permission('home-page-01-about-us');
        Route::post('/about-us', 'HomePageController@home_01_update_about_us')->permission('home-page-01-about-us');

        Route::get('/video-area', 'HomePageController@home_01_video_area')->name('admin.homeone.video.area')->permission('home-page-01-video-area');
        Route::post('/video-area', 'HomePageController@home_01_update_video_area')->permission('home-page-01-video-area');

        Route::get('/section-manage', 'HomePageController@home_01_section_manage')->name('admin.homeone.section.manage')->permission('home-page-01-section-manage');
        Route::post('/section-manage', 'HomePageController@home_01_update_section_manage')->permission('home-page-01-section-manage');
    });

    // WIDGET
    Route::group(['prefix' => 'widgets', 'namespace' => 'Admin'], function () {
        //widget manage
        Route::get('/all', 'WidgetsController@index')->name('admin.widgets')->permission('widgets-all');
        Route::post('/all', 'WidgetsController@new_widget')->name('admin.widgets.new')->permission('widgets-all');
        Route::post('/markup', 'WidgetsController@widget_markup')->name('admin.widgets.markup')->permission('widgets-markup');
        Route::post('/update', 'WidgetsController@update_widget')->name('admin.widgets.update')->permission('widgets-update');
        Route::post('/update/order', 'WidgetsController@update_order_widget')->name('admin.widgets.update.order')->permission('widgets-update-order');
        Route::post('/delete', 'WidgetsController@delete_widget')->name('admin.widgets.delete')->permission('widgets-delete');
    });

    // TOPBAR SETTINGS
    Route::group(['prefix' => 'topbar-settings', 'namespace' => 'Admin'], function () {
        Route::get('/all', "TopBarController@index")->name('admin.topbar.settings')->permission('topbar-settings-all');
        Route::post('/all', 'TopBarController@store')->permission('topbar-settings-all');
        Route::post('/update', 'TopBarController@update')->name('admin.topbar.update')->permission('topbar-settings-update');
        Route::post('/delete/{id}', 'TopBarController@delete')->name('admin.topbar.delete')->permission('topbar-settings-delete');
        Route::post('/bulk-action', 'TopBarController@bulk_action')->name('admin.topbar.bulk.action')->permission('topbar-settings-bulk-action');
    });

    //MENU MANAGE
    Route::group(['prefix' => 'menu', 'namespace' => 'Admin'], function () {
        Route::get('/', 'MenuController@index')->name('admin.menu')->permission('menu');
        Route::post('/new-menu', 'MenuController@store_new_menu')->name('admin.menu.new')->permission('menu-new-menu');
        Route::get('/edit/{id}', 'MenuController@edit_menu')->name('admin.menu.edit')->permission('menu-edit');
        Route::post('/update/{id}', 'MenuController@update_menu')->name('admin.menu.update')->permission('menu-update');
        Route::post('/delete/{id}', 'MenuController@delete_menu')->name('admin.menu.delete')->permission('menu-delete');
        Route::post('/default/{id}', 'MenuController@set_default_menu')->name('admin.menu.default')->permission('menu-default');
        Route::post('/mega-menu', 'MenuController@mega_menu_item_select_markup')->name('admin.mega.menu.item.select.markup')->permission('menu-mega-menu');
    });

    // Category Menu
    Route::group(['prefix' => 'category-menu', 'namespace' => 'Admin'], function () {
        Route::get('/', 'CategoryMenuController@index')->name('admin.category.menu.settings')->permission('category-menu');
        Route::post('/new-menu', 'CategoryMenuController@store_new_menu')->name('admin.category.menu.new')->permission('category-menu-new-menu');
        Route::get('/edit/{id}', 'CategoryMenuController@edit_menu')->name('admin.category.menu.edit')->permission('category-menu-edit');
        Route::post('/update/{id}', 'CategoryMenuController@update_menu')->name('admin.category.menu.update')->permission('category-menu-update');
        Route::post('/delete/{id}', 'CategoryMenuController@delete_menu')->name('admin.category.menu.delete')->permission('category-menu-delete');
        Route::post('/default/{id}', 'CategoryMenuController@set_default_menu')->name('admin.category.menu.default')->permission('category-menu-default');
        Route::post('/mega-menu', 'CategoryMenuController@mega_menu_item_select_markup')->name('admin.mega.category.menu.item.select.markup')->permission('category-menu-mega-menu');
        Route::post('/render-sub-category',[CategoryMenuController::class,"fetch_sub_category"])->name("admin.category.menu.sub_category")->permission('category-menu-render-sub-category');
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     *                           HOMEPAGE MANAGE
     * ----------------------------------------------------------------------------------------------------------------------------*/
    //homepage manage
    Route::prefix('home-page')->namespace('Admin')->group(function () {
        //Key Features
        Route::get('/key-features-area', 'HomePageController@key_features_section')->name('admin.home.key.features')->permission('home-page-key-features-area');
        Route::post('/key-features-area', 'HomePageController@update_key_features_section')->permission('home-page-key-features-area');

        //why-choose-us area
        Route::get('/why-choose-us-area-settings', 'HomePageController@why_choose_us_area')->name('admin.home.why.choose.us')->permission('home-page-why-choose-us-area-settings');
        Route::post('/why-choose-us-area-settings', 'HomePageController@update_why_choose_us_area')->permission('home-page-why-choose-us-area-settings');

        //call to action area
        Route::get('/call-to-action-settings', 'HomePageController@call_to_action_area')->name('admin.home.call.to.action')->permission('home-page-call-to-action-settings');
        Route::post('/call-to-action-settings', 'HomePageController@update_call_to_action_area')->permission('home-page-call-to-action-settings');

        //keyfeatures area
        Route::get('/keyfeatures-area-settings', 'HomePageController@keyfeatures_area')->name('admin.home.keyfeatures')->permission('home-page-keyfeatures-area-settings');
        Route::post('/keyfeatures-area-settings', 'HomePageController@update_keyfeatures_area')->permission('home-page-keyfeatures-area-settings');
        //price plan area
        Route::get('/price-plan-area-settings', 'HomePageController@price_plan_area')->name('admin.home.price.plan')->permission('home-page-price-plan-area-settings');
        Route::post('/price-plan-area-settings', 'HomePageController@update_price_plan_area')->permission('home-page-price-plan-area-settings');

        //latest blog area
        Route::get('/latest-blog-settings', 'HomePageController@latest_blog_area')->name('admin.home.blog.latest')->permission('home-page-latest-blog-settings');
        Route::post('/latest-blog-settings', 'HomePageController@update_latest_blog_area')->permission('home-page-latest-blog-settings');

        //section manage
        Route::get('/section-manage', 'HomePageController@section_manage')->name('admin.home.section.manage')->permission('home-page-section-manage');
        Route::post('/section-manage', 'HomePageController@update_section_manage')->permission('home-page-section-manage');
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     *                           CONTACT PAGE MANAGE
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'contact-page', 'namespace' => 'Admin'], function () {
        //contact page
        Route::get('/form-area', 'ContactPageController@contact_page_form_area')->name('admin.contact.page.form.area')->permission('contact-page-form-area');
        Route::post('/form-area', 'ContactPageController@contact_page_update_form_area')->permission('contact-page-form-area');
        Route::get('/map', 'ContactPageController@contact_page_map_area')->name('admin.contact.page.map')->permission('contact-page-map');
        Route::post('/map', 'ContactPageController@contact_page_update_map_area')->permission('contact-page-map');
        // section manage
        Route::get('/section-manage', 'ContactPageController@contact_page_section_manage')->name('admin.contact.page.section.manage')->permission('contact-page-section-manage');
        Route::post('/section-manage', 'ContactPageController@contact_page_update_section_manage')->permission('contact-page-section-manage');

        //contact info /** @todo delete */
        Route::get('/contact-info', 'ContactInfoController@index')->name('admin.contact.info')->permission('contact-page-contact-info');
        Route::post('/contact-info', 'ContactInfoController@store')->permission('contact-page-contact-info');
        Route::post('/contact-info/title', 'ContactInfoController@contact_info_title')->name('admin.contact.info.title')->permission('contact-page-contact-info-title');
        Route::post('/contact-info/update', 'ContactInfoController@update')->name('admin.contact.info.update')->permission('contact-page-contact-info-update');
        Route::post('/contact-info/delete/{id}', 'ContactInfoController@delete')->name('admin.contact.info.delete')->permission('contact-page-contact-info-delete');
        Route::post('/contact-info/bulk-action', 'ContactInfoController@bulk_action')->name('admin.contact.info.bulk.action')->permission('contact-page-contact-info-bulk-action');
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     *                           MEDIA UPLOAD ROUTE
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'media-upload', 'namespace' => 'Admin'], function () {
        Route::post('/alt', 'MediaUploadController@alt_change_upload_media_file')->name('admin.upload.media.file.alt.change')->permission('media-upload-alt');
        Route::get('/page', 'MediaUploadController@all_upload_media_images_for_page')->name('admin.upload.media.images.page')->permission('media-upload-page');
        Route::post('/delete', 'MediaUploadController@delete_upload_media_file')->name('admin.upload.media.file.delete')->permission('media-upload-delete');
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     *                          ADMIN DASHBOARD ROUTES
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['namespace' => 'Admin'], function () {
        //admin Profile
        Route::get('/settings', 'AdminDashboardController@admin_settings')->name('admin.profile.settings')->permission('settings');
        Route::get('/profile-update', 'AdminDashboardController@admin_profile')->name('admin.profile.update')->permission('profile-update');
        Route::post('/profile-update', 'AdminDashboardController@admin_profile_update')->permission('profile-update');
        Route::get('/password-change', 'AdminDashboardController@admin_password')->name('admin.password.change')->permission('password-change');
        Route::post('/password-change', 'AdminDashboardController@admin_password_chagne')->permission('password-change');
        //admin index
        Route::get('/', 'AdminDashboardController@adminIndex')->name('admin.home');
        Route::get('/health','AdminDashboardController@health')->name('admin.health');
        Route::get('/dark-mode-toggle', 'AdminDashboardController@dark_mode_toggle')->name('admin.dark.mode.toggle')->permission('dark-mode-toggle');
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     *                          BLOG PAGE MANAGE
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'blog', 'namespace' => 'Admin'], function () {
        Route::get('/', 'BlogController@index')->name('admin.blog')->permission('blog');
        Route::get('/new', 'BlogController@new_blog')->name('admin.blog.new')->permission('blog-new');
        Route::post('/new', 'BlogController@store_new_blog')->permission('blog-new');
        Route::post('/clone', 'BlogController@clone_blog')->name('admin.blog.clone')->permission('blog-clone');
        Route::get('/edit/{id}', 'BlogController@edit_blog')->name('admin.blog.edit')->permission('blog-edit');


        // Route::post('/update/{id}', 'BlogController@update_blog')->name('admin.blog.update')->permission('blog-update');
        Route::post('/update/{id}', [BlogController::class,"update_blog"])->name('admin.blog.update');


        Route::post('/delete/{id}', 'BlogController@delete_blog')->name('admin.blog.delete')->permission('blog-delete');
        Route::get('/category', 'BlogController@category')->name('admin.blog.category')->permission('blog-category');
        Route::post('/category', 'BlogController@new_category')->permission('blog-category');
        Route::post('/category/delete/{id}', 'BlogController@delete_category')->name('admin.blog.category.delete')->permission('blog-category-delete');
        Route::post('/category/update', 'BlogController@update_category')->name('admin.blog.category.update')->permission('blog-category-update');
        Route::post('/category/bulk-action', 'BlogController@category_bulk_action')->name('admin.blog.category.bulk.action')->permission('blog-category-bulk-action');
        Route::post('/blog-lang-by-cat', 'BlogController@Language_by_slug')->name('admin.blog.lang.cat')->permission('blog-blog-lang-by-cat');
        //blog page
        Route::get('/page-settings', 'BlogController@blog_page_settings')->name('admin.blog.page.settings')->permission('blog-page-settings');
        Route::post('/page-settings', 'BlogController@update_blog_page_settings')->permission('blog-page-settings');
        //blog single page
        Route::get('/single-settings', 'BlogController@blog_single_page_settings')->name('admin.blog.single.settings')->permission('blog-single-settings');
        Route::post('/single-settings', 'BlogController@update_blog_single_page_settings')->permission('blog-single-settings');
        //bulk action
        Route::post('/bulk-action', 'BlogController@bulk_action')->name('admin.blog.bulk.action')->permission('blog-bulk-action');
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     *                          FAQ ROUTES
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'faq', 'namespace' => 'Admin'], function () {
        Route::get('/', 'FaqController@index')->name('admin.faq')->permission('faq');
        Route::post('/', 'FaqController@store')->permission('faq');
        Route::post('/update-faq', 'FaqController@update')->name('admin.faq.update')->permission('faq-update-faq');
        Route::post('/delete-faq/{id}', 'FaqController@delete')->name('admin.faq.delete')->permission('faq-delete-faq');
        Route::post('/clone-faq', 'FaqController@clone')->name('admin.faq.clone')->permission('faq-clone-faq');
        Route::post('/faq/bulk-action', 'FaqController@bulk_action')->name('admin.faq.bulk.action')->permission('faq-faq-bulk-action');
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     *                          PAGES ROUTES
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'page', 'namespace' => 'Admin'], function () {
        Route::get('/all', 'PagesController@index')->name('admin.page')->permission('page-all');
        Route::get('/new', 'PagesController@new_page')->name('admin.page.new')->permission('page-new');
        Route::post('/new', 'PagesController@store_new_page')->permission('page-new');
        Route::get('/edit/{id}', 'PagesController@edit_page')->name('admin.page.edit')->permission('page-edit');
        Route::get('/page-image/{id}', 'PagesController@pageImage')->name('admin.page.image')->permission('page-edit');
        Route::post('/page-image-upload/{id}', 'PagesController@uploadPageImage')->name('admin.page.image.upload')->permission('page-edit');
        Route::get('/page-image-delete/{id}', 'PagesController@deletePageImage')->name('admin.page.image.delete')->permission('page-edit');
        Route::post('/page-image-edit/{id}', 'PagesController@editPageImage')->name('admin.page.image.edit')->permission('page-edit');
        Route::post('/update/{id}', 'PagesController@update_page')->name('admin.page.update')->permission('page-update');
        Route::post('/delete/{id}', 'PagesController@delete_page')->name('admin.page.delete')->permission('page-delete');
        Route::post('/bulk-action', 'PagesController@bulk_action')->name('admin.page.bulk.action')->permission('page-bulk-action');
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     *                          NAVBAR ROUTES
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'appearance-settings/navbar', 'namespace' => 'Admin'], function () {
        Route::get('/all', 'NavbarController@navbar_settings')->name('admin.navbar.settings')->permission('appearance-settings-navbar-all');
        Route::post('/all', 'NavbarController@update_navbar_settings')->permission('appearance-settings-navbar-all');
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     *                          HOME VARIANT ROUTES
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'appearance-settings/home-variant', 'namespace' => 'Admin'], function () {
        Route::get('/select', "AdminDashboardController@home_variant")->name('admin.home.variant')->permission('appearance-settings-home-variant-select');
        Route::post('/select', "AdminDashboardController@update_home_variant")->permission('appearance-settings-home-variant-select');
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     *                          TOP BAR ROUTES
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'appearance-settings/topbar', 'namespace' => 'Admin'], function () {
        Route::get('/all', "TopBarController@topbar_settings")->name('admin.topbar.settings')->permission('appearance-settings-topbar-all');
        Route::post('/all', "TopBarController@update_topbar_settings")->permission('appearance-settings-topbar-all');
        Route::post('/select-menu', "TopBarController@selectTopBarMenu")->name('admin.topbar.select.menu')->permission('appearance-settings-topbar-select-menu');
        Route::post('/new-social-item', 'TopBarController@new_social_item')->name('admin.new.social.item')->permission('appearance-settings-topbar-new-social-item');
        Route::post('/update-social-item', 'TopBarController@update_social_item')->name('admin.update.social.item')->permission('appearance-settings-topbar-update-social-item');
        Route::post('/delete-social-item/{id}', 'TopBarController@delete_social_item')->name('admin.delete.social.item')->permission('appearance-settings-topbar-delete-social-item');
    });

    /**---------------------------------------------------------------------------------------------------------------------------
     *                          GENERAL SETTINGS MANAGE
     * ----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'general-settings', 'namespace' => 'Admin'], function () {     //Upgrade Database
        Route::get('/database-upgrade', 'GeneralSettingsController@database_upgrade')->name('admin.general.database.upgrade')->permission('general-settings-database-upgrade');
        Route::post('/database-upgrade', 'GeneralSettingsController@database_upgrade_post')->permission('general-settings-database-upgrade');
        //Reading
        Route::get('/reading', 'GeneralSettingsController@reading')->name('admin.general.reading')->permission('general-settings-reading');
        Route::post('/reading', 'GeneralSettingsController@update_reading')->permission('general-settings-reading');
        //Reading
        Route::get('/others', 'GeneralSettingsController@others')->name('admin.general.others')->permission('general-settings-others');
        Route::post('/others', 'GeneralSettingsController@update_others')->permission('general-settings-others');

        //Navbar Global Variant
        Route::get('/global-variant-navbar', 'GeneralSettingsController@global_variant_navbar')->name('admin.general.global.variant.navbar')->permission('general-settings-global-variant-navbar');
        Route::post('/global-variant-navbar', 'GeneralSettingsController@update_global_variant_navbar')->permission('general-settings-global-variant-navbar');

        // Navbar Category Dropdown
        Route::get('/navbar-category-dropdown', 'GeneralSettingsController@navbar_category_dropdown')->name('admin.general.navbar.category.dropdown')->permission('general-settings-navbar-category-dropdown');
        Route::post('/navbar-category-dropdown', 'GeneralSettingsController@update_navbar_category_dropdown')->permission('general-settings-navbar-category-dropdown');

        //general settings
        Route::get('/site-identity', 'GeneralSettingsController@site_identity')->name('admin.general.site.identity')->permission('general-settings-site-identity');
        Route::post('/site-identity', 'GeneralSettingsController@update_site_identity')->permission('general-settings-site-identity');

        Route::get('/basic-settings', 'GeneralSettingsController@basic_settings')->name('admin.general.basic.settings')->permission('general-settings-basic-settings');
        Route::post('/basic-settings', 'GeneralSettingsController@update_basic_settings')->permission('general-settings-basic-settings');

        Route::get('/color-settings', 'GeneralSettingsController@color_settings')->name('admin.general.color.settings')->permission('general-settings-color-settings');
        Route::post('/color-settings', 'GeneralSettingsController@update_color_settings')->permission('general-settings-color-settings');

        Route::get('/seo-settings', 'GeneralSettingsController@seo_settings')->name('admin.general.seo.settings')->permission('general-settings-seo-settings');
        Route::post('/seo-settings', 'GeneralSettingsController@update_seo_settings')->permission('general-settings-seo-settings');

        Route::get('/scripts', 'GeneralSettingsController@scripts_settings')->name('admin.general.scripts.settings')->permission('general-settings-scripts');
        Route::post('/scripts', 'GeneralSettingsController@update_scripts_settings')->permission('general-settings-scripts');

        Route::get('/email-template', 'GeneralSettingsController@email_template_settings')->name('admin.general.email.template')->permission('general-settings-email-template');
        Route::post('/email-template', 'GeneralSettingsController@update_email_template_settings')->permission('general-settings-email-template');

        Route::get('/typography-settings', 'GeneralSettingsController@typography_settings')->name('admin.general.typography.settings')->permission('general-settings-typography-settings');
        Route::post('/typography-settings', 'GeneralSettingsController@update_typography_settings')->permission('general-settings-typography-settings');

        Route::post('/typography-settings/single', 'GeneralSettingsController@get_single_font_variant')->name('admin.general.typography.single')->permission('general-settings-typography-settings-single');

        Route::get('/cache-settings', 'GeneralSettingsController@cache_settings')->name('admin.general.cache.settings')->permission('general-settings-cache-settings');
        Route::post('/cache-settings', 'GeneralSettingsController@update_cache_settings')->permission('general-settings-cache-settings');

        Route::get('/page-settings', 'GeneralSettingsController@page_settings')->name('admin.general.page.settings')->permission('general-settings-page-settings');
        Route::post('/page-settings', 'GeneralSettingsController@update_page_settings')->permission('general-settings-page-settings');

        Route::get('/backup-settings', 'GeneralSettingsController@backup_settings')->name('admin.general.backup.settings')->permission('general-settings-backup-settings');
        Route::post('/backup-settings', 'GeneralSettingsController@update_backup_settings')->permission('general-settings-backup-settings');

        Route::post('/backup-settings/delete', 'GeneralSettingsController@delete_backup_settings')->name('admin.general.backup.settings.delete')->permission('general-settings-backup-settings-delete');
        Route::post('/backup-settings/restore', 'GeneralSettingsController@restore_backup_settings')->name('admin.general.backup.settings.restore')->permission('general-settings-backup-settings-restore');

        Route::get('/update-system', 'GeneralSettingsController@update_system')->name('admin.general.update.system')->permission('general-settings-update-system');
        Route::post('/update-system', 'GeneralSettingsController@update_system_version')->permission('general-settings-update-system');

        Route::get('/license-setting', 'GeneralSettingsController@license_settings')->name('admin.general.license.settings')->permission('general-settings-icense-setting');
        Route::post('/license-setting', 'GeneralSettingsController@update_license_settings')->permission('general-settings-license-setting');

        Route::get('/custom-css', 'GeneralSettingsController@custom_css_settings')->name('admin.general.custom.css')->permission('general-settings-custom-css');
        Route::post('/custom-css', 'GeneralSettingsController@update_custom_css_settings')->permission('general-settings-custom-css');

        Route::get('/gdpr-settings', 'GeneralSettingsController@gdpr_settings')->name('admin.general.gdpr.settings')->permission('general-settings-gdpr-settings');
        Route::post('/gdpr-settings', 'GeneralSettingsController@update_gdpr_cookie_settings')->permission('general-settings-gdpr-settings');
        //update script
        Route::get('/update-script', 'ScriptUpdateController@index')->name('admin.general.script.update')->permission('general-settings-update-script');
        Route::post('/update-script', 'ScriptUpdateController@update_script')->permission('general-settings-update-script');
        //custom js
        Route::get('/custom-js', 'GeneralSettingsController@custom_js_settings')->name('admin.general.custom.js')->permission('general-settings-custom-js');
        Route::post('/custom-js', 'GeneralSettingsController@update_custom_js_settings')->permission('general-settings-custom-js');
        //smtp settings
        Route::get('/smtp-settings', 'GeneralSettingsController@smtp_settings')->name('admin.general.smtp.settings')->permission('general-settings-smtp-settings');

        Route::post('/smtp-settings', 'GeneralSettingsController@update_smtp_settings')->permission('general-settings-smtp-settings');
        Route::post('/smtp-settings/test', 'GeneralSettingsController@test_smtp_settings')->name('admin.general.smtp.settings.test')->permission('general-settings-smtp-settings-test');
        //payment gateway
        Route::get('/payment-settings', 'GeneralSettingsController@payment_settings')->name('admin.general.payment.settings')->permission('general-settings-payment-settings');
        Route::post('/payment-settings', 'GeneralSettingsController@update_payment_settings')->permission('general-settings-payment-settings');

        //popup
        Route::get('/popup-settings', 'GeneralSettingsController@popup_settings')->name('admin.general.popup.settings')->permission('general-settings-popup-settings');
        Route::post('/popup-settings', 'GeneralSettingsController@update_popup_settings')->permission('general-settings-popup-settings');
        //rss feed
        Route::get('/rss-settings', 'GeneralSettingsController@rss_feed_settings')->name('admin.general.rss.feed.settings')->permission('general-settings-rss-settings');
        Route::post('/rss-settings', 'GeneralSettingsController@update_rss_feed_settings')->permission('general-settings-rss-settings');
        //update script
        Route::get('/update-script', 'GeneralSettingsController@update_script_settings')->name('admin.general.update.script.settings')->permission('general-settings-update-script');
        Route::post('/update-script', 'GeneralSettingsController@sote_update_script_settings')->permission('general-settings-update-script');
        //sitemap
        Route::get('/sitemap-settings', 'GeneralSettingsController@sitemap_settings')->name('admin.general.sitemap.settings')->permission('general-settings-sitemap-settings');
        Route::post('/sitemap-settings', 'GeneralSettingsController@update_sitemap_settings')->permission('general-settings-sitemap-settings');
        Route::post('/sitemap-settings/delete', 'GeneralSettingsController@delete_sitemap_settings')->name('admin.general.sitemap.settings.delete')->permission('general-settings-sitemap-settings-delete');

        Route::controller(LicenseController::class)->middleware('auth:admin')->group(function (){
            Route::post('/license-setting-verify', 'license_key_generate')->name('admin.general.license.key.generate');
            Route::get('/update-check', 'update_version_check')->name('admin.general.update.version.check');
            Route::post('/download-update/{productId}/{tenant}', 'updateDownloadLatestVersion')->name('admin.general.update.download.settings');
            Route::get('/software-update-setting', 'software_update_check_settings')->name('admin.general.software.update.settings');
        });
    });

    //language
    Route::group(['prefix' => 'languages', 'namespace' => 'Admin'], function () {
        Route::get('/', 'LanguageController@index')->name('admin.languages')->permission('languages');
        Route::get('/words/frontend/{id}', 'LanguageController@frontend_edit_words')->name('admin.languages.words.frontend')->permission('languages-words/frontend');
        Route::get('/words/backend/{id}', 'LanguageController@backend_edit_words')->name('admin.languages.words.backend')->permission('languages-words-backend');
        Route::post('/words/update/{id}', 'LanguageController@update_words')->name('admin.languages.words.update')->permission('languages-words-update');
        Route::post('/new', 'LanguageController@store')->name('admin.languages.new')->permission('languages-new');
        Route::post('/update', 'LanguageController@update')->name('admin.languages.update')->permission('languages-update');
        Route::post('/delete/{id}', 'LanguageController@delete')->name('admin.languages.delete')->permission('languages-delete');
        Route::post('/default/{id}', 'LanguageController@make_default')->name('admin.languages.default')->permission('languages-default');
        Route::post('/clone', 'LanguageController@clone_languages')->name('admin.languages.clone')->permission('languages-clone');
        Route::post('/add-new-string', 'LanguageController@add_new_string')->name('admin.languages.add.string')->permission('languages-add-new-string');
        Route::post('/languages/regenerate-source-text','LanguageController@regenerate_source_text')->name('admin.languages.regenerate.source.texts')->permission('languages-languages-regenerate-source-text');
    });

    /** ------------------------------------------
     *              PAGE BUILDER
     * ------------------------------------------ */
    Route::group(['prefix' => 'page-builder', 'namespace' => 'Admin'], function () {
        Route::post('/update', 'PageBuilderController@update_addon_content')->name('admin.page.builder.update')->permission('page-builder-update');
        Route::post('/new', 'PageBuilderController@store_new_addon_content')->name('admin.page.builder.new')->permission('page-builder-new');
        Route::post('/delete', 'PageBuilderController@delete')->name('admin.page.builder.delete')->permission('page-builder-delete');
        Route::get('/dynamic-page/{type}/{id}', 'PageBuilderController@dynamicpage_builder')->name('admin.dynamic.page.builder')->permission('page-builder-dynamic-page');
        Route::post('/dynamic-page', 'PageBuilderController@update_dynamicpage_builder')->name('admin.dynamic.page.builder.store')->permission('page-builder-dynamic-page');
    });

    /** ------------------------------------------
     * FORM BUILDER ROUTES
     * ------------------------------------------ */
    Route::prefix('form-builder')->group(function () {
        /*-------------------------
            CUSTOM FORM BUILDER
        --------------------------*/
        Route::group(['prefix' => 'custom'], function () {
            Route::get('/all', 'Admin\CustomFormBuilderController@all')->name('admin.form.builder.all')->permission('form-builder-custom-all');
            Route::post('/new', 'Admin\CustomFormBuilderController@store')->name('admin.form.builder.store')->permission('form-builder-custom-new');
            Route::get('/edit/{id}', 'Admin\CustomFormBuilderController@edit')->name('admin.form.builder.edit')->permission('form-builder-custom-edit');
            Route::post('/update', 'Admin\CustomFormBuilderController@update')->name('admin.form.builder.update')->permission('form-builder-custom-update');
            Route::post('/delete/{id}', 'Admin\CustomFormBuilderController@delete')->name('admin.form.builder.delete')->permission('form-builder-custom-delete');
            Route::post('/bulk-action', 'Admin\CustomFormBuilderController@bulk_action')->name('admin.form.builder.bulk.action')->permission('form-builder-custom-bulk-action');
        });

        /*-------------------------
         GET IN TOUCH FORM ROUTES
        --------------------------*/
        Route::get('/get-in-touch', 'FormBuilderController@get_in_touch_form_index')->name('admin.form.builder.get.in.touch');
        Route::post('/get-in-touch', 'FormBuilderController@update_get_in_touch_form');
        /*-------------------------
        SERVICE QUERY FORM ROUTES
       --------------------------*/
        Route::get('/service-query', 'FormBuilderController@service_query_index')->name('admin.form.builder.service.query')->permission('form-builder-service-query');
        Route::post('/service-query', 'FormBuilderController@update_service_query')->permission('form-builder-service-query');
        /*-------------------------
        CASE STUDY FORM ROUTES
       --------------------------*/
        Route::get('/case-study-query', 'FormBuilderController@case_study_query_index')->name('admin.form.builder.case.study.query')->permission('form-builder-case-study-query');
        Route::post('/case-study-query', 'FormBuilderController@update_case_study_query')->permission('form-builder-case-study-query');
        /*-------------------------
        QUOTE FORM ROUTES
       --------------------------*/
        Route::get('/quote-form', 'FormBuilderController@quote_form_index')->name('admin.form.builder.quote')->permission('form-builder-quote-form');
        Route::post('/quote-form', 'FormBuilderController@update_quote_form')->permission('form-builder-quote-form');

        /*-------------------------
        ORDER FORM ROUTES
       --------------------------*/
        Route::get('/order-form', 'FormBuilderController@order_form_index')->name('admin.form.builder.order')->permission('form-builder-order-form');
        Route::post('/order-form', 'FormBuilderController@update_order_form')->permission('form-builder-order-form');
        /*-------------------------
          CONTACT FORM ROUTES
          --------------------------*/
        Route::get('/contact-form', 'FormBuilderController@contact_form_index')->name('admin.form.builder.contact')->permission('form-builder-contact-form');
        Route::post('/contact-form', 'FormBuilderController@update_contact_form')->permission('form-builder-contact-form');

        /*-------------------------
           ESTIMATE FORM ROUTES
         --------------------------*/
        Route::get('/estimate', 'FormBuilderController@estimate_form_index')->name('admin.form.builder.estimate.form')->permission('form-builder-estimate');
        Route::post('/estimate', 'FormBuilderController@update_estimate_form')->permission('form-builder-estimate');
    });

    //currency rate
    Route::get('/currency-rate', 'CurrencyRateController@index')->name('admin.currency-rate')->permission('currency-rate');
    Route::post('/currency-rate', 'CurrencyRateController@update')->name('admin.currency-rate.update')->permission('currency-rate');

}); //End admin-home


/**----------------------------------------------------------------------

 *    ADMIN MEDIA UPLOAD BUTTON, KEEP IT SEPARATED FOR DEMO PURPOSE
 * -----------------------------------------------------------------------*/
Route::group(['middleware' => ['setlang:backend', 'auth:admin'], 'prefix' => 'admin'], function () {
    Route::post('/international-shipping/update', [InternationalShippingController::class, 'updateSettings'])
        ->name('admin.international-shipping.update');
    Route::post('/international-shipping/toggle-status', [InternationalShippingController::class, 'toggleStatus'])
        ->name('admin.international-shipping.toggle-status');
    Route::get('/international-shipping/{method}', [InternationalShippingController::class, 'getSettings'])
        ->name('admin.international-shipping.settings');
});

$product_page_slug = getSlugFromReadingSetting('product_page') ?? 'product';
Route::group(['prefix' => $product_page_slug, 'as' => 'frontend.products.', 'middleware' => ['globalVariable', 'maintains_mode']], function () use ($product_page_slug) {
    Route::get("download-invoice/{id}", "FrontendProductController@download_invoice")->name("download-invoice");
});

Route::get("product-search", [FrontendController::class, "search"])->name("frontend.ajax.products.search");

Route::middleware("globalVariable")->as('frontend.')->controller(PaymentGatewayController::class)->group(function (){
    Route::post('paytm-ipn', 'paytm_ipn')->name('paytm.ipn');
    Route::post('toyyibpay-ipn', 'toyyibpay_ipn')->name('toyyibpay.ipn');
    Route::get('mollie-ipn', 'mollie_ipn')->name('mollie.ipn');
    Route::get('stripe-ipn', 'stripe_ipn')->name('stripe.ipn');
    Route::post('razorpay-ipn', 'razorpay_ipn')->name('razorpay.ipn');
    Route::post('payfast-ipn', 'payfast_ipn')->name('payfast.ipn');
    Route::get('flutterwave/ipn', 'flutterwave_ipn')->name('flutterwave.ipn');
    Route::get('paystack-ipn', 'paystack_ipn')->name('paystack.ipn');
    Route::get('midtrans-ipn', 'midtrans_ipn')->name('midtrans.ipn');
    Route::post('cashfree-ipn', 'cashfree_ipn')->name('cashfree.ipn');
    Route::get('instamojo-ipn', 'instamojo_ipn')->name('instamojo.ipn');
    Route::get('paypal-ipn', 'paypal_ipn')->name('paypal.ipn');
    Route::get('marcadopago-ipn', 'marcadopago_ipn')->name('marcadopago.ipn');
    Route::get('squareup-ipn', 'squareup_ipn')->name('squareup.ipn');
    Route::post('cinetpay-ipn', 'cinetpay_ipn')->name('cinetpay.ipn');
    Route::post('paytabs-ipn', 'paytabs_ipn')->name('paytabs.ipn');
    Route::post('billplz-ipn', 'billplz_ipn')->name('billplz.ipn');
    Route::post('zitopay-ipn', 'zitopay_ipn')->name('zitopay.ipn');
    Route::post('pagali-ipn', 'pagali_ipn' )->name('pagali.ipn');
    Route::get('authorize-ipn', 'authorize_ipn')->name('authorizenet.ipn');
    Route::post('siteways-ipn', 'siteways_ipn')->name('siteways.ipn');
    Route::get('transactioncloud-ipn', 'transactionclud_api')->name('transactionclud.ipn');
    Route::get('wipay-ipn', 'wipay_ipn')->name('wipay.ipn');
    Route::post('kineticpay-ipn', 'kineticPay_ipn')->name('kineticPay.ipn');
    Route::get('senangpay-ipn', 'senangpay_ipn')->name('senangpay.ipn');
    Route::post('salt-ipn', 'salt_ipn')->name('saltpay.ipn');
    Route::post('iyzipay-ipn', 'iyzipay_ipn')->name('iyzipay.ipn');

    Route::post('/order-confirm','order_payment_form')->name('order.payment.form');

    Route::get('/order-success/{id}','order_payment_success')->name('order.payment.success');
    Route::get('/order-cancel/{id}','order_payment_cancel')->name('order.payment.cancel');
    Route::get('/order-cancel-static','order_payment_cancel_static')->name('order.payment.cancel.static');
    Route::get('/order-confirm/{id}','order_confirm')->name('order.confirm');
});


Route::group(['middleware' => ['setlang:frontend', 'globalVariable', 'maintains_mode']], function () {
    Route::get('/vendor/{slug?}/products', 'FrontendController@dynamic_single_page')->name('frontend.vendor.product');
    Route::get('/{slug?}', 'FrontendController@dynamic_single_page')->name('frontend.dynamic.page');
});

Route::post('/set-currency', 'FrontendController@setCurrency')->name('set.currency');

Route::post('/dhl/calculate', [DHLShippingController::class, 'calculate'])->name('dhl.calculate');
Route::post('/dhl/calu', [DHLShippingController::class, 'calu'])->name('dhl.calu');

Route::get('/get-cities', [DHLShippingController::class, 'getCities'])->name('get.cities');


