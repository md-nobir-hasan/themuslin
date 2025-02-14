<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FlashMsg;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ModulePageSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->middleware('permission:page-settings-wishlist-page', ['only', [
            'wishlistPageSettings',
            'storeWishlistPageSettings',
        ]]);
        $this->middleware('permission:page-settings-cart-page', ['only', [
            'cartPageSettings',
            'storeCartPageSettings',
        ]]);
        $this->middleware('permission:page-settings-checkout-page', ['only', [
            'checkoutPageSettings',
            'storeCheckoutPageSettings',
        ]]);
        $this->middleware('permission:page-settings-compare-page', ['only', [
            'comparePageSettings',
            'storeComparePageSettings',
        ]]);
        $this->middleware('permission:page-settings-login-register-page', ['only', [
            'userAuthPageSettings',
            'storeUserAuthPageSettings',
        ]]);
        $this->middleware('permission:page-settings-shop-page', ['only', [
            'shopPage',
            'storeShopPage',
        ]]);
        $this->middleware('permission:page-settings-product-details-page', ['only', [
            'productDetailPage',
            'storeProductDetailPage',
        ]]);
    }

    /** ==============================================
     *              WISHLIST SETTINGS
      ============================================== */
    public function wishlistPageSettings()
    {
        return view('backend.settings.wishlist');
    }

    public function storeWishlistPageSettings(Request $request)
    {
        $all_field_rules = [
            'empty_wishlist_image' => 'nullable|string',
            'empty_wishlist_text' => 'nullable|string',
            'send_to_cart_text' => 'nullable|string',
            'clear_wishlist_text' => 'nullable|string',
        ];
        $this->validateAndInsert($request, $all_field_rules);

        return back()->with(FlashMsg::settings_update());
    }

    /** ==============================================
     *              CART SETTINGS
      ============================================== */
    public function cartPageSettings()
    {
        return view('backend.settings.cart');
    }

    public function storeCartPageSettings(Request $request)
    {
        $all_field_rules = [
            'empty_cart_text' => 'nullable|string|max:191',
            'back_to_home_text' => 'nullable|string|max:191',
            'empty_cart_image' => 'nullable|string|max:191',
            'clear_cart_text' => 'nullable|string|max:191',
            'update_cart_text' => 'nullable|string|max:191',
            'cart_continue_shopping_text' => 'nullable|string|max:191',
            'cart_coupon_discount_title' => 'nullable|string|max:191',
            'cart_coupon_discount_subtitle' => 'nullable|string|max:191',
            'cart_coupon_discount_placeholder' => 'nullable|string|max:191',
            'cart_apply_coupon_text' => 'nullable|string|max:191',
            'cart_total_title' => 'nullable|string|max:191',
            'cart_proceed_to_checkout_text' => 'nullable|string|max:191',
        ];
        $this->validateAndInsert($request, $all_field_rules);

        return back()->with(FlashMsg::settings_update());
    }

    public function checkoutPageSettings()
    {
        $all_pages = Page::select('id', 'title', 'slug')->where('status', 'publish')->get();

        return view('backend.settings.checkout', compact('all_pages'));
    }

    public function storeCheckoutPageSettings(Request $request)
    {
        $all_field_rules = [
            'checkout_page_no_product_text' => 'nullable|string|max:191',
            'returning_customer_text' => 'nullable|string|max:191',
            'toggle_login_text' => 'nullable|string|max:191',
            'checkout_username' => 'nullable|string|max:191',
            'checkout_password' => 'nullable|string|max:191',
            'checkout_remember_text' => 'nullable|string|max:191',
            'checkout_forgot_password' => 'nullable|string|max:191',
            'checkout_login_btn_text' => 'nullable|string|max:191',
            'have_coupon_text' => 'nullable|string|max:191',
            'enter_coupon_text' => 'nullable|string|max:191',
            'coupon_placeholder' => 'nullable|string|max:191',
            'apply_coupon_btn_text' => 'nullable|string|max:191',
            'checkout_billing_section_title' => 'nullable|string|max:191',
            'checkout_billing_city' => 'nullable|string|max:191',
            'checkout_billing_zipcode' => 'nullable|string|max:191',
            'checkout_billing_address' => 'nullable|string|max:191',
            'checkout_billing_email' => 'nullable|string|max:191',
            'checkout_billing_phone' => 'nullable|string|max:191',
            'checkout_order_note' => 'nullable|string|max:191',
            'create_account_text' => 'nullable|string|max:191',
            'create_account_username' => 'nullable|string|max:191',
            'create_account_password' => 'nullable|string|max:191',
            'create_account_confirmed_password' => 'nullable|string|max:191',
            'ship_to_another_text' => 'nullable|string|max:191',
            'shipping_name' => 'nullable|string|max:191',
            'shipping_country' => 'nullable|string|max:191',
            'shipping_state' => 'nullable|string|max:191',
            'shipping_city' => 'nullable|string|max:191',
            'shipping_zipcode' => 'nullable|string|max:191',
            'shipping_address' => 'nullable|string|max:191',
            'shipping_email' => 'nullable|string|max:191',
            'shipping_phone' => 'nullable|string|max:191',
            'order_summary_title' => 'nullable|string|max:191',
            'subtotal_text' => 'nullable|string|max:191',
            'discount_text' => 'nullable|string|max:191',
            'vat_text' => 'nullable|string|max:191',
            'shipping_text' => 'nullable|string|max:191',
            'total_text' => 'nullable|string|max:191',
            'checkout_place_order' => 'nullable|string|max:191',
            'checkout_return_cart' => 'nullable|string|max:191',
            'checkout_page_terms_text' => 'nullable|string|max:191',
            'checkout_page_terms_link_url' => 'nullable|string|max:191',
        ];

        $this->validateAndInsert($request, $all_field_rules);

        return back()->with(FlashMsg::settings_update());
    }

    /** ==============================================
     *              COMPARE SETTINGS
      ============================================== */
    public function comparePageSettings()
    {
        return view('backend.settings.compare');
    }

    public function storeComparePageSettings(Request $request)
    {
        $all_field_rules = [
            'compare_title' => 'nullable|string',
            'compare_subtitle' => 'nullable|string',
            'compare_empty_image' => 'nullable|string',
            'compare_empty_text' => 'nullable|string',
        ];
        $this->validateAndInsert($request, $all_field_rules);

        return back()->with(FlashMsg::settings_update());
    }

    /** ==============================================
     *              LOGIN/REGISTER SETTINGS
      ============================================== */
    public function userAuthPageSettings()
    {
        $pages = Page::select("title", "slug")->where("status", "publish")->get();

        return view('backend.settings.userAuth', compact("pages"));
    }

    public function storeUserAuthPageSettings(Request $request)
    {
        $all_field_rules = [
            'user_auth_page_logo' => 'nullable|string',
            'toc_page_link' => 'nullable|string',
            'privacy_policy_link' => 'nullable|string',
            'user_auth_page_side_image' => 'nullable|string',
        ];
        $this->validateAndInsert($request, $all_field_rules);

        $social_links = [];

        foreach ($request->social_icon_link ?? [] as $key => $socialIconLink) {
            if ($socialIconLink) {
                $social_links[] = [
                    'icon' => $request->social_icon_icon[$key],
                    'link' => $request->social_icon_link[$key],
                ];
            }
        }

        update_static_option('user_auth_page_social_links', json_encode($social_links));

        return back()->with(FlashMsg::settings_update());
    }

    public function shopPage()
    {
        return view('backend.settings.shopPage');
    }

    public function storeShopPage(Request $request)
    {
        $all_field_rules = [
            'sidebar_visibility' => 'nullable|string',
            'sidebar_position' => 'nullable|string',
            'default_item_count' => 'nullable|string',
            'shop_column_count' => 'nullable|string',

            'shop_product_search' => 'nullable|string',
            'shop_filter_by_price' => 'nullable|string',
            'shop_filter_by_category' => 'nullable|string',
            'shop_filter_by_average_rating' => 'nullable|string',
            'shop_filter_by_tags' => 'nullable|string',
        ];
        $this->validateAndInsert($request, $all_field_rules);

        return back()->with(FlashMsg::settings_update());
    }

    public function productDetailPage()
    {
        return view('backend.settings.product-detail-page');
    }

    public function storeProductDetailPage(Request $request)
    {
        $all_field_rules = [
            'sidebar_visibility' => 'nullable|string',
            'product_in_stock_text' => 'nullable|string',
            'product_out_of_stock_text' => 'nullable|string',
            'add_to_cart_text' => 'nullable|string',
            'description_tab_text' => 'nullable|string',
            'additional_information_text' => 'nullable|string',
            'reviews_text' => 'nullable|string',
            'your_reviews_text' => 'nullable|string',
            'write_your_feedback_text' => 'nullable|string',
            'post_your_feedback_text' => 'nullable|string',
            'no_rating_text' => 'nullable|string',
            'related_product_text' => 'nullable|string',
            'related_product_image' => 'nullable|string',
            'sidebar_status' => 'nullable|string',
            'sidebar_position' => 'nullable|string',
        ];
        $this->validateAndInsert($request, $all_field_rules);

        return back()->with(FlashMsg::settings_update());
    }

    /** ==============================================
     *              HELPER FUNCTIONS
     * ============================================== */
    private function validateAndInsert($request, $all_field_rules)
    {
        $request->validate($all_field_rules);
        foreach ($all_field_rules as $filed => $rule) {
            update_static_option($filed, $request->$filed);
        }
    }
}
