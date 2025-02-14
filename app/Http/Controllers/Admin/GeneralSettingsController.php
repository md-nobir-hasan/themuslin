<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FlashMsg;
use App\Language;
use App\Mail\BasicMail;
use App\Page;
use App\PaymentGateway;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductSubCategory;
use Spatie\Sitemap\SitemapGenerator;
use Xgenious\XgApiClient\Facades\XgApiClient;

class GeneralSettingsController extends Controller
{
    private $base_path = 'backend.general-settings.';

    public function database_upgrade(): Factory|View|Application
    {
        return view('backend.general-settings.database-upgrade');
    }

    public function database_upgrade_post(Request $request): RedirectResponse
    {
        setEnvValue(['APP_ENV' => 'local']);
        Artisan::call('migrate', ['--force' => true]);
        if (class_exists('StaticOptionUpgrade')) {
            Artisan::call('db:seed', ['--force' => true, '--class' => 'StaticOptionUpgrade']);
        }
        setEnvValue(['APP_ENV' => 'production']);

        return back()->with(FlashMsg::item_update('Database Upgrade Success'));
    }

    public function smtp_settings()
    {
        return view($this->base_path.'smtp-settings');
    }

    public function others()
    {
        $all_pages = Page::where(['status' => 'publish'])->get();

        return view('backend.general-settings.others', compact('all_pages'));
    }

    public function update_others(Request $request)
    {
        // update on database
        update_static_option('others_page_terms_and_condition_page_id', $request->others_page_terms_and_condition_page_id);

        return back()->with(['msg' => 'Successfully Updated']);
    }

    public function update_smtp_settings(Request $request)
    {
        $request->validate([
            'site_smtp_mail_host' => 'required|string',
            'site_smtp_mail_port' => 'required|string',
            'site_smtp_mail_username' => 'required|string',
            'site_smtp_mail_password' => 'required|string',
            'site_smtp_mail_encryption' => 'required|string',
        ]);

        update_static_option('site_smtp_mail_mailer', $request->site_smtp_mail_mailer);
        update_static_option('site_smtp_mail_host', $request->site_smtp_mail_host);
        update_static_option('site_smtp_mail_port', $request->site_smtp_mail_port);
        update_static_option('site_smtp_mail_username', $request->site_smtp_mail_username);
        update_static_option('site_smtp_mail_password', $request->site_smtp_mail_password);
        update_static_option('site_smtp_mail_encryption', $request->site_smtp_mail_encryption);

        setEnvValue([
            'MAIL_MAILER' => $request->site_smtp_mail_mailer,
            'MAIL_HOST' => $request->site_smtp_mail_host,
            'MAIL_PORT' => $request->site_smtp_mail_port,
            'MAIL_USERNAME' => $request->site_smtp_mail_username,
            'MAIL_PASSWORD' => '"'.$request->site_smtp_mail_password.'"',
            'MAIL_ENCRYPTION' => $request->site_smtp_mail_encryption,
        ]);

        return redirect()->back()->with(['msg' => __('SMTP Settings Updated...'), 'type' => 'success']);
    }

    public function test_smtp_settings(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'message' => 'required|string',
        ]);
        $res_data = [
            'msg' => __('Mail Send Success'),
            'type' => 'success',
        ];
        try {
            Mail::to($request->email)->send(new BasicMail([
                'subject' => $request->subject,
                'message' => $request->message,
            ]));
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'type' => 'danger',
                'msg' => $e->getMessage(),
            ]);
        }

        if (Mail::failures()) {
            $res_data = [
                'msg' => __('Mail Send Failed'),
                'type' => 'danger',
            ];
        }

        return redirect()->back()->with($res_data);
    }

    public function custom_js_settings()
    {
        $custom_js = '/* Write Custom js Here */';
        if (file_exists('assets/frontend/js/dynamic-script.js')) {
            $custom_js = file_get_contents('assets/frontend/js/dynamic-script.js');
        }

        return view($this->base_path.'custom-js')->with(['custom_js' => $custom_js]);
    }

    public function update_custom_js_settings(Request $request)
    {
        file_put_contents('assets/frontend/js/dynamic-script.js', $request->custom_js_area);

        return redirect()->back()->with(['msg' => __('Custom Script Added Success...'), 'type' => 'success']);
    }

    public function gdpr_settings()
    {
        return view($this->base_path.'gdpr');
    }

    public function update_gdpr_cookie_settings(Request $request)
    {

        $request->validate([
            'site_gdpr_cookie_enabled' => 'nullable|string|max:191',
            'site_gdpr_cookie_expire' => 'required|string|max:191',
            'site_gdpr_cookie_delay' => 'required|string|max:191',
        ]);

        $all_language = Language::orderBy('default', 'desc')->get();
        foreach ($all_language as $lang) {
            $request->validate([
                'site_gdpr_cookie_title' => 'nullable|string',
                'site_gdpr_cookie_message' => 'nullable|string',
                'site_gdpr_cookie_more_info_label' => 'nullable|string',
                'site_gdpr_cookie_more_info_link' => 'nullable|string',
                'site_gdpr_cookie_accept_button_label' => 'nullable|string',
                'site_gdpr_cookie_decline_button_label' => 'nullable|string',
            ]);
            $_title = 'site_gdpr_cookie_title';
            $_message = 'site_gdpr_cookie_message';
            $_more_info_label = 'site_gdpr_cookie_more_info_label';
            $_more_info_link = 'site_gdpr_cookie_more_info_link';
            $_accept_button_label = 'site_gdpr_cookie_accept_button_label';
            $decline_button_label = 'site_gdpr_cookie_decline_button_label';

            update_static_option($_title, $request->$_title);
            update_static_option($_message, $request->$_message);
            update_static_option($_more_info_label, $request->$_more_info_label);
            update_static_option($_more_info_link, $request->$_more_info_link);
            update_static_option($_accept_button_label, $request->$_accept_button_label);
            update_static_option($decline_button_label, $request->$decline_button_label);
        }

        update_static_option('site_gdpr_cookie_delay', $request->site_gdpr_cookie_delay);
        update_static_option('site_gdpr_cookie_enabled', $request->site_gdpr_cookie_enabled);
        update_static_option('site_gdpr_cookie_expire', $request->site_gdpr_cookie_expire);

        return redirect()->back()->with(['msg' => __('GDPR Cookie Settings Updated..'), 'type' => 'success']);
    }

    public function cache_settings()
    {
        return view($this->base_path.'cache-settings');
    }

    public function update_cache_settings(Request $request)
    {

        $request->validate([
            'cache_type' => 'required|string',
        ]);

        Artisan::call($request->cache_type.':clear');

        return redirect()->back()->with(['msg' => __('Cache Cleaned...'), 'type' => 'success']);
    }

    public function license_settings()
    {
        return view($this->base_path.'license-settings');
    }

    public function update_license_settings(Request $request)
    {
        $request->validate([
            'site_license_key' => 'required|string|max:191',
            'envato_username' => 'required|string|max:191',
        ]);

        $result = XgApiClient::activeLicense($request->site_license_key,$request->envato_username);
        $type = "danger";
        $msg = __("could not able to verify your license key, please try after sometime, if you still face this issue, contact support");
        if (!empty($result["success"]) && $result["success"]){
            update_static_option('site_license_key', $request->site_license_key);
            update_static_option('item_license_status', $result['success'] ? 'verified' : "");
            update_static_option('item_license_msg', $result['message']);
            $type = $result['success'] ? 'success' : "danger";
            $msg = $result['message'];
        }

        return redirect()->back()->with(['msg' => $msg, 'type' => $type]);
    }

    public function custom_css_settings()
    {
        $custom_css = '/* Write Custom Css Here */';
        if (file_exists('assets/frontend/css/dynamic-style.css')) {
            $custom_css = file_get_contents('assets/frontend/css/dynamic-style.css');
        }

        return view($this->base_path.'custom-css')->with(['custom_css' => $custom_css]);
    }

    public function update_custom_css_settings(Request $request)
    {
        file_put_contents('assets/frontend/css/dynamic-style.css', $request->custom_css_area);

        return redirect()->back()->with(['msg' => __('Custom Style Added Success...'), 'type' => 'success']);
    }

    public function typography_settings()
    {
        $all_google_fonts = file_get_contents('assets/frontend/fonts/google-fonts.json');

        return view($this->base_path.'typograhpy')->with(['google_fonts' => json_decode($all_google_fonts)]);
    }

    public function get_single_font_variant(Request $request): JsonResponse
    {
        $all_google_fonts = file_get_contents('assets/frontend/fonts/google-fonts.json');
        $decoded_fonts = json_decode($all_google_fonts, true);

        return response()->json($decoded_fonts[$request->font_family]);
    }

    public function update_typography_settings(Request $request): RedirectResponse
    {
        $request->validate([
            'body_font_family' => 'required|string|max:191',
            'body_font_variant' => 'required',
            'heading_font' => 'nullable|string',
            'heading_font_family' => 'nullable|string|max:191',
            'heading_font_variant' => 'nullable',
            'extra_font' => 'nullable|string',
            'extra_font_family' => 'nullable|string|max:191',
            'extra_font_variant' => 'nullable',
        ]);

        $save_data = [
            'body_font_family',
            'heading_font_family',
            'extra_font_family',
        ];
        foreach ($save_data as $item) {
            update_static_option($item, $request->$item);
        }
        $body_font_variant = ! empty($request->body_font_variant) ? $request->body_font_variant : ['regular'];
        $heading_font_variant = ! empty($request->heading_font_variant) ? $request->heading_font_variant : ['regular'];
        $extra_font_variant = ! empty($request->extra_font_variant) ? $request->extra_font_variant : ['regular'];

        update_static_option('heading_font', $request->heading_font);
        update_static_option('extra_font', $request->extra_font);
        update_static_option('body_font_variant', serialize($body_font_variant));
        update_static_option('heading_font_variant', serialize($heading_font_variant));
        update_static_option('extra_font_variant', serialize($extra_font_variant));

        return redirect()->back()->with(['msg' => __('Typography Settings Updated..'), 'type' => 'success']);
    }

    public function page_settings(): Factory|View|Application
    {
        return view($this->base_path.'page-settings');
    }

    public function update_page_settings(Request $request): RedirectResponse
    {
        $all_page_slug_settings = [
            'about_page',
            'product_page',
            'faq_page',
            'blog_page',
            'contact_page',
            'image_gallery_page',
        ];

        foreach ($all_page_slug_settings as $slug) {
            $request->validate([$slug.'_slug' => 'required|string|max:191']);
            if ($request->has($slug.'_slug')) {
                $fi = $slug.'_slug';
                update_static_option($slug.'_slug', Str::slug($request->$fi));
            }
        }

        foreach ($all_page_slug_settings as $slug) {
            $page_name = $slug.'_name';
            $meta_tags = $slug.'_meta_tags';
            $meta_description = $slug.'_meta_description';
            update_static_option($page_name, $request->$page_name);
            update_static_option($meta_tags, $request->$meta_tags);
            update_static_option($meta_description, $request->$meta_description);
        }

        return redirect()->back()->with(['msg' => __('Settings Updated..'), 'type' => 'success']);
    }

    public function basic_settings(): Factory|View|Application
    {
        return view($this->base_path.'basic');
    }

    public function update_basic_settings(Request $request): RedirectResponse
    {
        $request->validate([
            'site_secondary_color' => 'nullable|string',
            'site_sticky_navbar_enabled' => 'nullable|string',
            'disable_backend_preloader' => 'nullable|string',
            'disable_user_email_verify' => 'nullable|string',
            'og_meta_image_for_site' => 'nullable|string',
            'site_admin_panel_nav_sticky' => 'nullable|string',
            'site_force_ssl_redirection' => 'nullable|string',
        ]);

        $request->validate([
            'site_title' => 'nullable|string',
            'site_tag_line' => 'nullable|string',
            'site_footer_copyright' => 'nullable|string',
        ]);
        $_title = 'site_title';
        $_tag_line = 'site_tag_line';
        $_footer_copyright = 'site_footer_copyright';

        update_static_option($_title, $request->$_title);
        update_static_option($_tag_line, $request->$_tag_line);
        update_static_option($_footer_copyright, $request->$_footer_copyright);

        $all_fields = [
            'enable_buy_now_button_on_shop_page',
            'site_frontend_nav_sticky',
            'og_meta_image_for_site',
            'site_rtl_enabled',
            'site_maintenance_mode',
            'site_payment_gateway',
            'site_sticky_navbar_enabled',
            'disable_backend_preloader',
            'disable_user_email_verify',
            'site_force_ssl_redirection',
            'preloader_status',
        ];

        foreach ($all_fields as $field) {
            update_static_option($field, $request->$field);
        }

        return redirect()->back()->with(['msg' => __('Basic Settings Update Success'), 'type' => 'success']);
    }

    public function color_settings()
    {
        return view($this->base_path.'color-settings');
    }

    public function update_color_settings(Request $request)
    {
        $request->validate([
            'site_color' => 'required|string',
            'site_secondary_color' => 'required|string',
            'site_heading_color' => 'required|string',
            'site_special_color' => 'required|string',
            'site_paragraph_color' => 'required|string',
            'site_form_bg_color' => 'required|string',
            'site_footer_bg_color' => 'required|string',
        ]);

        $all_fields = [
            'site_color',
            'site_secondary_color',
            'site_heading_color',
            'site_special_color',
            'site_paragraph_color',
            'site_form_bg_color',
            'site_footer_bg_color',
        ];

        foreach ($all_fields as $field) {
            update_static_option($field, $request->$field);
        }

        return redirect()->back()->with(['msg' => __('Color Settings Update Success'), 'type' => 'success']);
    }

    public function seo_settings(): Factory|View|Application
    {
        return view($this->base_path.'seo');
    }

    public function update_seo_settings(Request $request): RedirectResponse
    {

        $request->validate([
            'site_meta_tags' => 'required|string',
            'site_meta_description' => 'required|string',
        ]);

        $site_tags = 'site_meta_tags';
        $site_description = 'site_meta_description';

        update_static_option($site_tags, $request->$site_tags);
        update_static_option($site_description, $request->$site_description);

        return redirect()->back()->with(['msg' => __('SEO Settings Update Success'), 'type' => 'success']);
    }

    public function scripts_settings()
    {
        return view($this->base_path.'thid-party');
    }

    public function update_scripts_settings(Request $request)
    {

        $request->validate([
            'site_disqus_key' => 'nullable|string',
            'tawk_api_key' => 'nullable|string',
            'site_third_party_tracking_code' => 'nullable|string',
            'site_google_analytics' => 'nullable|string',
            'site_google_captcha_v3_secret_key' => 'nullable|string',
            'site_google_captcha_v3_site_key' => 'nullable|string',
        ]);

        update_static_option('site_disqus_key', $request->site_disqus_key);
        update_static_option('site_google_analytics', $request->site_google_analytics);
        update_static_option('tawk_api_key', $request->tawk_api_key);
        update_static_option('site_third_party_tracking_code', $request->site_third_party_tracking_code);
        update_static_option('site_google_captcha_v3_site_key', $request->site_google_captcha_v3_site_key);
        update_static_option('site_google_captcha_v3_secret_key', $request->site_google_captcha_v3_secret_key);

        $fields = [
            'site_google_captcha_v3_secret_key',
            'site_google_captcha_v3_site_key',
            'site_third_party_tracking_code',
            'site_google_analytics',
            'tawk_api_key',
            'site_disqus_key',
            'enable_google_login',
            'google_client_id',
            'google_client_secret',
            'enable_facebook_login',
            'facebook_client_id',
            'facebook_client_secret',
        ];
        foreach ($fields as $field) {
            update_static_option($field, $request->$field);
        }

        setEnvValue([
            'FACEBOOK_CLIENT_ID' => $request->facebook_client_id,
            'FACEBOOK_CLIENT_SECRET' => $request->facebook_client_secret,
            'FACEBOOK_CALLBACK_URL' => route('facebook.callback'),
            'GOOGLE_CLIENT_ID' => $request->google_client_id,
            'GOOGLE_CLIENT_SECRET' => $request->google_client_secret,
            'GOOGLE_CALLBACK_URL' => route('google.callback'),
        ]);

        return redirect()->back()->with(['msg' => __('Third Party Scripts Settings Updated..'), 'type' => 'success']);
    }

    public function email_template_settings()
    {
        return view($this->base_path.'email-template');
    }

    public function update_email_template_settings(Request $request)
    {

        $request->validate([
            'site_global_email' => 'required|string',
            'site_global_email_template' => 'required|string',
        ]);

        update_static_option('site_global_email', $request->site_global_email);
        update_static_option('site_global_email_template', $request->site_global_email_template);

        return redirect()->back()->with(['msg' => __('Email Settings Updated..'), 'type' => 'success']);
    }

    public function site_identity()
    {
        return view($this->base_path.'site-identity');
    }

    public function update_site_identity(Request $request)
    {
        $request->validate([
            'site_logo' => 'nullable|string|max:191',
            'site_favicon' => 'nullable|string|max:191',
            'site_breadcrumb_bg' => 'nullable|string|max:191',
            'site_white_logo' => 'nullable|string|max:191',
        ]);
        update_static_option('site_logo', $request->site_logo);
        update_static_option('site_favicon', $request->site_favicon);
        update_static_option('site_breadcrumb_bg', $request->site_breadcrumb_bg);
        update_static_option('site_white_logo', $request->site_white_logo);

        return redirect()->back()->with([
            'msg' => __('Site Identity Has Been Updated..'),
            'type' => 'success',
        ]);
    }

    public function payment_settings()
    {
        $all_gateway = PaymentGateway::all();

        return view('backend.general-settings.payment-gateway', compact('all_gateway'));
    }

    public function update_payment_settings(Request $request)
    {
        $request->validate([
            'site_global_currency' => 'nullable|string|max:191',
            'site_currency_symbol_position' => 'nullable|string|max:191',
            'site_default_payment_gateway' => 'nullable|string|max:191',
        ]);

        $global_currency = get_static_option('site_global_currency');

        $save_data = [
            'site_global_currency',
            'site_global_payment_gateway',
            'site_usd_to_ngn_exchange_rate',
            'site_euro_to_ngn_exchange_rate',
            'site_currency_symbol_position',
            'site_default_payment_gateway',

            'site_'.strtolower($global_currency).'_to_idr_exchange_rate',
            'site_'.strtolower($global_currency).'_to_inr_exchange_rate',
            'site_'.strtolower($global_currency).'_to_ngn_exchange_rate',
            'site_'.strtolower($global_currency).'_to_zar_exchange_rate',
            'site_'.strtolower($global_currency).'_to_brl_exchange_rate',
        ];

        foreach ($save_data as $item) {
            update_static_option($item, $request->$item);
        }

        $all_gateway = PaymentGateway::all();
        foreach ($all_gateway as $gateway) {
            //  if manual payament gatewya then save description into database
            $image_name = $gateway->name.'_logo';
            $status_name = $gateway->name.'_gateway';
            $test_mode_name = $gateway->name.'_test_mode';

            $credentials = ! empty($gateway->credentials) ? json_decode($gateway->credentials) : [];
            $update_credentials = [];
            foreach ($credentials as $cred_name => $cred_val) {
                $crd_req_name = $gateway->name.'_'.$cred_name;
                $update_credentials[$cred_name] = $request->$crd_req_name;
            }

            PaymentGateway::where(['name' => $gateway->name])->update([
                'image' => $request->$image_name,
                'status' => isset($request->$status_name) ? 1 : 0,
                'test_mode' => isset($request->$test_mode_name) ? 1 : 0,
                'credentials' => json_encode($update_credentials),
            ]);
        }

        Artisan::call('cache:clear');

        return redirect()->back()->with([
            'msg' => __('Payment Settings Updated..'),
            'type' => 'success',
        ]);
    }

    public function sitemap_settings()
    {
        $all_sitemap = glob('sitemap/*');

        return view($this->base_path.'sitemap-settings')->with(['all_sitemap' => $all_sitemap]);
    }

    public function update_sitemap_settings(Request $request)
    {
        $request->validate([
            'site_url' => 'required|url',
            'title' => 'nullable|string',
        ]);

        $title = $request->title ? $request->title : time();

        SitemapGenerator::create(Str::slug($request->site_url))->writeToFile('sitemap/sitemap-'.$title.'.xml');

        return redirect()->back()->with([
            'msg' => __('Sitemap Generated..'),
            'type' => 'success',
        ]);
    }

    public function delete_sitemap_settings(Request $request)
    {
        if (file_exists($request->sitemap_name)) {
            @unlink($request->sitemap_name);
        }

        return redirect()->back()->with(['msg' => __('Sitemap Deleted...'), 'type' => 'danger']);
    }

    public function rss_feed_settings()
    {
        return view($this->base_path.'rss-feed-settings');
    }

    public function update_rss_feed_settings(Request $request)
    {
        $request->validate([
            'site_rss_feed_url' => 'required|string',
            'site_rss_feed_title' => 'required|string',
            'site_rss_feed_description' => 'required|string',
        ]);
        update_static_option('site_rss_feed_description', $request->site_rss_feed_description);
        update_static_option('site_rss_feed_title', $request->site_rss_feed_title);
        update_static_option('site_rss_feed_url', $request->site_rss_feed_url);

        $env_val['RSS_FEED_URL'] = $request->site_rss_feed_url ? '"'.$request->site_rss_feed_url.'"' : '"rss-feeds"';
        $env_val['RSS_FEED_TITLE'] = $request->site_rss_feed_title ? '"'.$request->site_rss_feed_title.'"' : '"'.get_static_option('site_title').'"';
        $env_val['RSS_FEED_DESCRIPTION'] = $request->site_rss_feed_description ? '"'.$request->site_rss_feed_description.'"' : '"'.get_static_option('site_tag_line').'"';

        setEnvValue([
            'RSS_FEED_URL' => $env_val['RSS_FEED_URL'],
            'RSS_FEED_TITLE' => $env_val['RSS_FEED_TITLE'],
            'RSS_FEED_DESCRIPTION' => $env_val['RSS_FEED_DESCRIPTION'],
            'RSS_FEED_LANGUAGE' => get_default_language(),
        ]);

        return redirect()->back()->with([
            'msg' => __('RSS Settings Update..'),
            'type' => 'success',
        ]);
    }

    public function popup_settings()
    {
        $all_languages = Language::orderBy('default', 'desc')->get();
        $all_popup = PopupBuilder::all()->groupBy('lang');

        return view($this->base_path.'popup-settings')->with(['all_popup' => $all_popup, 'all_languages' => $all_languages]);
    }

    public function update_popup_settings(Request $request)
    {
        $request->validate([
            'popup_enable_status' => 'nullable|string',
            'popup_delay_time' => 'nullable|string',
        ]);
        update_static_option('popup_enable_status', $request->popup_enable_status);
        update_static_option('popup_delay_time', $request->popup_delay_time);
        $all_languages = Language::orderBy('default', 'desc')->get();
        foreach ($all_languages as $lang) {
            $request->validate([
                'popup_selected_'.$lang->slug.'_id' => 'nullable|string',
            ]);
            $field = 'popup_selected_'.$lang->slug.'_id';
            update_static_option($field, $request->$field);
        }

        return redirect()->back()->with(['msg' => __('Settings Updated'), 'type' => 'success']);
    }

    public function update_script_settings()
    {
        return view($this->base_path.'update-script');
    }

    /* ==============================================
     *          Dynamic page functions
     * ============================================== */
    public function reading()
    {
        $all_pages = Page::where(['status' => 'publish'])->get();

        return view('backend.general-settings.reading', compact('all_pages'));
    }

    public function update_reading(Request $request)
    {
        return $this->update_fields($request, [
            'home_page' => 'nullable|string',
            'product_page' => 'nullable|string',
            'blog_page' => 'nullable|string',
        ]);
    }

    public function global_variant_navbar()
    {
        return view('backend.general-settings.navbar-global-variant');
    }

    public function update_global_variant_navbar(Request $request)
    {
        $request->validate(['global_navbar_variant' => 'nullable|string']);

        if ($request->has('global_navbar_variant')) {
            update_static_option('global_navbar_variant', $request->global_navbar_variant);
        }

        return redirect()->back()->with(FlashMsg::settings_update());
    }

    public function navbar_category_dropdown()
    {
        $all_categories = ProductCategory::where('status', 'publish')->get();
        $all_sub_categories = ProductSubCategory::where('status', 'publish')->get();
        $selected_sub_categories = null;
        $navbar_categories = json_decode((string) get_static_option('navbar_category_dropdown'), true) ?? null;

        $sub_category_ids = [];

        if ($navbar_categories) {
            foreach ($navbar_categories as $navbar_category) {
                if (! empty($navbar_category['subcategories'])) {
                    $sub_category_ids = array_merge($sub_category_ids, $sub_category_ids);
                }
            }
        }

        if (! empty($sub_category_ids)) {
            $selected_sub_categories = ProductSubCategory::whereIn('id', $sub_category_ids)->get();
        }

        return view('backend.general-settings.navbar-category-dropdown', compact(
            'all_categories',
            'navbar_categories',
            'all_sub_categories',
            'selected_sub_categories',
        ));
    }

    /**
     * Saved data structure
     * categories = [
     *                  1:  [ subcategories: [2, 5, 6...], style: 'list' ],
     *                  2:  [ subcategories: [7, 9, 12...], style: 'thumbnail' ],
     *              ]
     */
    public function update_navbar_category_dropdown(Request $request): RedirectResponse
    {
        $request->validate([
            'navbar_categories' => 'required|array',
            'navbar_sub_categories' => 'nullable|array',
            'navbar_sub_category_styles' => 'nullable|array',
        ]);

        $store_data = [];

        foreach ($request->navbar_categories as $category_id) {
            $store_data[$category_id] = [];

            // if category details selected
            if (! empty($request->navbar_sub_categories) && ! empty($request->navbar_sub_categories[$category_id])) {
                // list subcategory ids under category
                foreach ($request->navbar_sub_categories[$category_id] as $sub_category_id) {
                    $store_data[$category_id]['subcategories'][] = $sub_category_id;
                }

                // set style for this category
                if (! empty($request->navbar_sub_category_styles) && ! empty($request->navbar_sub_category_styles[$category_id])) {
                    $store_data[$category_id]['style'] = $request->navbar_sub_category_styles[$category_id];
                }
            } else {
                // if only category is selected (will show only category option in the navbar dropdown)
                $store_data[$category_id] = null;
            }
        }

        update_static_option('navbar_category_dropdown', json_encode($store_data));

        return redirect()->back()->with(FlashMsg::settings_update());
    }

    /**
     * Validate and update static settings
     */
    private function update_fields(Request $request, array $field_rules): RedirectResponse
    {
        $request->validate($field_rules);

        foreach ($field_rules as $field => $rules) {
            update_static_option($field, $request->$field);
        }

        return redirect()->back()->with(FlashMsg::settings_update());
    }
}
