<?php

namespace App\Helpers;

class HomePageStaticSettings
{
    public static function default_settings(){
        return [
            'site_title',
            'site_logo',
            'white_logo'
        ];
    }
    public function home_01(){
        $list = [
            'home_page_01_about_us_subtitle',
            'home_page_01_about_us_title',
            'home_page_01_about_us_description',
            'home_page_01_about_us_lists',
            'home_page_01_about_us_right_image',
            'homepage_01_feature_section_icon',
            'homepage_01_feature_section_title',
            'homepage_01_feature_section_description',
            'homepage_01_feature_section_url',
            'home_page_01_cta_area_background_image',
            'home_page_01_cta_area_signature_image',
            'home_page_01_cta_area_title',
            'home_page_01_cta_area_video_url',
            'home_page_01_latest_news_subtitle',
            'home_page_01_latest_news_title',
            'home_page_01_topbar_info_list_icon_icon',
            'home_page_01_topbar_info_list_text'
        ];
        return array_merge(self::default_settings(),$list);
    }
    public function home_02(){
        $list = [
            'site_title',
            'home_page_01_about_us_subtitle',
            'home_page_01_topbar_info_list_text',
            'home_page_01_topbar_info_list_text',
            'home_page_navbar_button_status',
            'homepage_01_feature_section_icon',
            'homepage_01_feature_section_title',
            'homepage_01_feature_section_description',
            'homepage_01_feature_section_url',
            'home_page_02_about_us_left_image',
            'home_page_01_about_us_subtitle',
            'home_page_01_about_us_title',
            'home_page_01_about_us_description',
            'home_page_01_about_us_lists',
            'home_page_02_about_us_icon',
            'home_page_02_about_us_short_description',
            'home_page_02_about_us_right_bottom_image',
            'home_page_01_cta_area_background_image',
            'home_page_01_cta_area_title',
            'home_page_01_cta_area_video_url',
            'home_page_02_coutnerup_background_image',
            'home_page_01_latest_news_subtitle',
            'home_page_01_latest_news_title'
        ];
        return array_merge(self::default_settings(),$list);
    }
    public function home_03(){
        $list = [
            'home_page_01_topbar_info_list_icon_icon',
            'home_page_01_topbar_info_list_text',
            'homepage_01_feature_section_icon',
            'homepage_01_feature_section_title',
            'homepage_01_feature_section_description',
            'homepage_01_feature_section_url',

            'home_page_03_about_us_right_image',
            'home_page_01_about_us_subtitle',
            'home_page_01_about_us_title',
            'home_page_01_about_us_description',
            'home_page_01_about_us_lists',
            'home_page_01_cta_area_background_image',
            'home_page_01_cta_area_title',
            'home_page_01_cta_area_video_url',
            'home_page_02_coutnerup_background_image',
            'home_page_01_latest_news_subtitle',
            'home_page_01_latest_news_title'
        ];
        return array_merge(self::default_settings(),$list);
    }

    public static function get_home_field($homepage_id){
        $new_self = new self();
        $home_var = 'home_'.$homepage_id;
        return $new_self->$home_var();
    }
}




