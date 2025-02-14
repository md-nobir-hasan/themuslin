<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FlashMsg;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;

class HomePageController extends Controller
{
    public $base_path;

    public function __construct()
    {
        $this->base_path = 'backend.pages.home.home-01.';
    }

    public function home_01_about_us()
    {
        return view($this->base_path.'about-us');
    }

    public function home_01_update_about_us(Request $request)
    {

        $request->validate([
            'home_page_01_about_us_title' => 'nullable|string',
            'home_page_01_about_us_subtitle' => 'nullable|string',
            'home_page_01_about_us_description' => 'nullable|string',
            'home_page_01_about_us_lists' => 'nullable|string',
            'home_page_02_about_us_short_description' => 'nullable|string',

            'home_page_01_about_us_right_image' => 'nullable|string|max:191',
            'home_page_02_about_us_left_image' => 'nullable|string|max:191',
            'home_page_02_about_us_icon' => 'nullable|string|max:191',
            'home_page_02_about_us_right_bottom_image' => 'nullable|string|max:191',
            'home_page_03_about_us_right_image' => 'nullable|string|max:191',
        ]);

        $save_data = [
            'home_page_01_about_us_title',
            'home_page_01_about_us_subtitle',
            'home_page_01_about_us_description',
            'home_page_01_about_us_lists',
            'home_page_02_about_us_short_description',

            'home_page_01_about_us_right_image',
            'home_page_02_about_us_left_image',
            'home_page_02_about_us_right_bottom_image',
            'home_page_03_about_us_right_image',
            'home_page_02_about_us_icon',
        ];
        foreach ($save_data as $item) {
            if ($request->has($item)) {
                update_static_option($item, $request->$item);
            }
        }

        return redirect()->back()->with(FlashMsg::settings_update());
    }

    public function home_01_latest_news()
    {
        return view($this->base_path.'latest-news');
    }

    public function home_01_update_latest_news(Request $request)
    {
        $request->validate([
            'home_page_01_latest_news_items' => 'required',
        ], [
            'home_page_01_latest_news_items.required' => __('total item field is required'),
        ]);

        $request->validate([
            'home_page_01_latest_news_title' => 'nullable|string',
            'home_page_01_latest_news_subtitle' => 'nullable|string',
        ]);
        $fields = [
            'home_page_01_latest_news_title',
            'home_page_01_latest_news_subtitle',
        ];
        foreach ($fields as $field) {
            update_static_option($field, $request->$field);
        }

        update_static_option('home_page_01_latest_news_items', $request->home_page_01_latest_news_items);

        return redirect()->back()->with(FlashMsg::settings_update());
    }

    public function home_01_feature_area()
    {
        return view($this->base_path.'feature-area');
    }

    public function home_01_update_feature_area(Request $request)
    {

        $request->validate([
            'homepage_01_feature_section_icon' => 'required|array',
            'homepage_01_feature_section_icon.*' => 'required|string',
            'homepage_01_feature_section_url' => 'required|array',
            'homepage_01_feature_section_url.*' => 'required|string',
            'homepage_01_feature_section_title' => 'required|array',
            'homepage_01_feature_section_title.*' => 'required|string',
            'homepage_01_feature_section_description' => 'required|array',
            'homepage_01_feature_section_description.*' => 'required|string',
        ]);

        $field_list = [
            'homepage_01_feature_section_icon',
            'homepage_01_feature_section_url',
            'homepage_01_feature_section_title',
            'homepage_01_feature_section_description',
        ];

        foreach ($field_list as $field) {
            $value = $request->$field ?? [];
            update_static_option($field, json_encode($value));
        }

        return redirect()->back()->with(FlashMsg::settings_update());
    }

    public function home_01_case_study_area()
    {
        return view($this->base_path.'case-study');
    }

    public function home_01_update_case_study_area(Request $request)
    {
        $request->validate([
            'home_page_01_case_study_items' => 'nullable|string',
            'home_page_02_case_study_background_image' => 'nullable|string',
        ]);
        $all_language = Language::orderBy('default', 'desc')->get();
        foreach ($all_language as $lang) {
            $request->validate([
                'home_page_01_'.$lang->slug.'_case_study_title' => 'nullable|string',
                'home_page_01_'.$lang->slug.'_case_study_description' => 'nullable|string',
            ]);
            $field_name = 'home_page_01_'.$lang->slug.'_case_study_title';
            $field_name_two = 'home_page_01_'.$lang->slug.'_case_study_description';
            update_static_option($field_name, $request->$field_name);
            update_static_option($field_name_two, $request->$field_name_two);
        }

        update_static_option('home_page_01_case_study_items', $request->home_page_01_case_study_items);
        update_static_option('home_page_02_case_study_background_image', $request->home_page_02_case_study_background_image);

        return redirect()->back()->with(FlashMsg::settings_update());
    }

    public function home_01_section_manage()
    {
        return view('backend.pages.section-manage');
    }

    public function home_01_update_section_manage(Request $request)
    {

        $request->validate([
            'home_page_header_slider_section_status' => 'nullable|string',
            'home_page_key_feature_section_status' => 'nullable|string',
            'home_page_about_us_section_status' => 'nullable|string',
            'home_page_video_section_status' => 'nullable|string',
            'home_page_latest_blog_section_status' => 'nullable|string',
        ]);

        $fields = [
            'home_page_header_slider_section_status',
            'home_page_key_feature_section_status',
            'home_page_about_us_section_status',
            'home_page_video_section_status',
            'home_page_latest_blog_section_status',
        ];
        foreach ($fields as $field) {
            update_static_option($field, $request->$field);
        }

        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return redirect()->back()->with(FlashMsg::settings_update());
    }

    public function home_01_latest_cause_area()
    {
        return view($this->base_path.'latest-cause');
    }

    public function home_01_video_area()
    {
        return view($this->base_path.'cta-area');
    }

    public function home_01_update_video_area(Request $request)
    {
        $request->validate([
            'home_page_01_cta_area_signature_image' => 'nullable|string',
            'home_page_01_cta_area_background_image' => 'nullable|string',
            'home_page_01_cta_area_video_url' => 'nullable|string',
            'home_page_01_cta_area_title' => 'nullable|string',
        ]);

        $_cta_area_title = 'home_page_01_cta_area_title';
        if ($request->has($_cta_area_title)) {
            update_static_option($_cta_area_title, $request->$_cta_area_title);

        }

        $all_fields = [
            'home_page_01_cta_area_video_url',
            'home_page_01_cta_area_background_image',
            'home_page_01_cta_area_signature_image',
        ];

        foreach ($all_fields as $field) {
            if ($request->has($field)) {
                update_static_option($field, $request->$field);
            }
        }

        return redirect()->back()->with(FlashMsg::settings_update());
    }
}
