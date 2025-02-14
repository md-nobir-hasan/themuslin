<?php

namespace App\WidgetsBuilder\Widgets;


use App\Language;
use App\Widgets;
use App\WidgetsBuilder\WidgetBase;

class AboutUsWidget extends WidgetBase
{
    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $image_val = $widget_saved_values['site_logo'] ?? '';
        $image_preview = '';
        $image_field_label = __('Upload Image');
        if (!empty($widget_saved_values)) {
            $image_markup = render_image_markup_by_attachment_id($widget_saved_values['site_logo']);
            $image_preview = '<div class="attachment-preview"><div class="thumbnail"><div class="centered">' . $image_markup . '</div></div></div>';
            $image_field_label = __('Change Image');
        }

        $output .= '<div class="form-group"><label for="site_logo"><strong>' . __('Logo') . '</strong></label>';
        $output .= '<div class="media-upload-btn-wrapper"><div class="img-wrap">' . $image_preview . '</div><input type="hidden" name="site_logo" value="' . $image_val . '">';
        $output .= '<button type="button" class="btn btn-info btn-xs media_upload_form_btn" data-btntitle="Select Image" data-modaltitle="Upload Image" data-bs-toggle="modal" data-bs-target="#media_upload_modal">';
        $output .= $image_field_label . '</button></div>';
        $output .= '<small class="form-text text-muted">' . __('allowed image format: jpg,jpeg,png. Recommended image size 160x50') . '</small></div>';
        //start multi langual tab option


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
        $image_val = $widget_saved_values['site_logo'] ?? '';

        $output = $this->widget_before(); //render widget before content

        $output .= '<div class="about_us_widget">';
        $output .= render_image_markup_by_attachment_id($image_val, 'footer-logo');
        $output .= '<p>' . purify_html($description) . '</p>';
        $output .= '</div>';

        $output .= $this->widget_after(); // render widget after content

        return $output;
    }

    public function widget_title(){
        return __('About Us');
    }

}