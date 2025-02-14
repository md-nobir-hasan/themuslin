<?php

namespace App\Http\Middleware;

use App\Blog;
use App\Language;
use App\Menu;
//use App\SocialIcons;
//use App\SupportInfo;
use App\SocialIcons;
use App\Widgets;
use App\WidgetsBuilder;
use Closure;
use App\StaticOption;

class GlobalVariableMiddleware
{
    public function handle($request, Closure $next)
    {
        $lang = !empty(session()->get('lang')) ? session()->get('lang') : Language::where('default', 1)->first()->slug;
        //make a function to call all static option by home page
        $static_option_arr = [
            'language_select_option',
            'site_google_analytics',
            'home_page_01_topbar_info_list_icon_icon',
            'og_meta_image_for_site',
            'site_color',
            'site_logo',
            'site_white_logo',
            'site_main_color_two',
            'site_secondary_color',
            'site_heading_color',
            'site_paragraph_color',
            'heading_font',
            'heading_font_family',
            'body_font_family',
            'site_rtl_enabled',
            'blog_page_slug',
            'service_page_slug',
            'about_page_slug',
            'team_page_slug',
            'faq_page_slug',
            'contact_page_slug',
            'career_with_us_page_slug',
            'knowledgebase_page_slug',
            'site_third_party_tracking_code',
            'site_favicon',
            'home_page_variant',
            'item_license_status',
            'site_script_unique_key',
            'home_page_navbar_button_status',
            'home_page_navbar_button_url',
            'home_page_navbar_button_icon',
            'home_page_navbar_button_icon',
            'home_page_navbar_button_subtitle',
            'home_page_navbar_button_title',
            'site_meta_description',
            'site_meta_tags',
            'site_title',
            'site_tag_line',
            'home_page_01_topbar_info_list_text',
            'navbar_right_text',
            'navbar_right_info'
        ];

        $static_field_data = StaticOption::whereIn('option_name', $static_option_arr)->get()->mapWithKeys(function ($item) {
            return [$item->option_name => $item->option_value];
        })->toArray();

        $all_social_item = SocialIcons::all();
        $all_language = Language::where('status', 'publish')->get();
        $primary_menu = Menu::where(['status' => 'default'])->first();

        $shareData = [
            'global_static_field_data' => $static_field_data,
            'all_social_item' => $all_social_item,
            'all_language' => $all_language,
            'primary_menu' => optional($primary_menu)->id,
            'user_select_lang_slug' => $lang
        ];

        view()->composer('*', function ($view) use($shareData) {
            $view->with($shareData);
        });

        return $next($request);
    }
}
