<?php

namespace App\WidgetsBuilder\Widgets;


use App\Language;
use App\SocialIcons;
use App\Widgets;
use App\WidgetsBuilder\WidgetBase;

class AboutUsWidgetTwo extends WidgetBase
{
    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $description = $widget_saved_values['description'] ?? '';
        $output .= '<div class="form-group"><textarea name="description"  class="form-control" cols="30" rows="5" placeholder="' . __('Description') . '">' . $description . '</textarea></div>';

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $widget_saved_values = $this->get_settings();
        $description = $widget_saved_values['description'] ?? '';
        $all_social_item = SocialIcons::all();

        $output = $this->widget_before(); //render widget before content

        $output .= '<div class="footer-inner mt-4">';
        $output .= '<h6 class="widget-title logo-style-one footer-logo-wrapper">'. render_image_markup_by_attachment_id(get_static_option("site_logo"), 'footer-logo') .'</h6>';
        $output .= '<h6 class="widget-title logo-style-two footer-logo-wrapper">'. render_image_markup_by_attachment_id(get_static_option("site_white_logo"), 'footer-logo') .'</h6>';
        $output .= '<p class="widget-para mt-4">' . purify_html($description) . '</p>';

        if(!empty($all_social_item) && $all_social_item->count()):
            $output .= '<ul class="footer-social-list mt-4">';
            foreach ($all_social_item as $social_item):
                $output .= '<li class="lists">
                        <a href="'. $social_item->url .'">
                            <i class="'.$social_item->icon .' icon"></i>
                        </a>
                    </li>';
            endforeach;
            $output .= '</ul>';
        endif;
        $output .= '</div>';

        $output .= $this->widget_after(); // render widget after content

        return $output;
    }

    public function widget_title(){
        return __('About Us: 02');
    }

}