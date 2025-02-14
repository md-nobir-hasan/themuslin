<?php


namespace App\WidgetsBuilder\Widgets;


use App\WidgetsBuilder\WidgetBase;

class ImageWidget extends WidgetBase
{

    public function admin_render()
    {
        //  Implement admin_render() method.
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $image_val = $widget_saved_values['single_image'] ?? '';
        $image_url = $widget_saved_values['image_url'] ?? '';
        $image_preview = '';
        if (!empty($widget_saved_values)) {
            $image_markup = render_image_markup_by_attachment_id($widget_saved_values['single_image']);
            $image_preview = '<div class="attachment-preview"><div class="thumbnail"><div class="centered">' . $image_markup . '</div></div></div>';
        }
        $output .= '<div class="form-group"><label for="single_image"><strong>' . __('Image') . '</strong></label>';
        $output .= '<div class="media-upload-btn-wrapper"><div class="img-wrap">' . $image_preview . '</div><input type="hidden" name="single_image" value="' . $image_val . '">';
        $output .= '<button type="button" class="btn btn-info btn-xs media_upload_form_btn" data-btntitle="Select Image" data-modaltitle="Upload Image" data-bs-toggle="modal" data-bs-target="#media_upload_modal">';
        $output .= __('Select Image') . '</button></div></div>';
        $output .= '<div class="form-group"><label for="url"><strong>' . __('URL') . '</strong></label><input type="text" name="image_url" class="form-control" value="' . $image_url . '"></div>';

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        //  Implement frontend_render() method.
        $widget_saved_values = $this->get_settings();
        $image_val =  $widget_saved_values['single_image'] ?? '';
        $image_url =  $widget_saved_values['image_url'] ?? '';
        $output = $this->widget_before(); //render widget before content
        $output .= '<div class="single-wrap"><a href="'.$image_url.'">'.render_image_markup_by_attachment_id($image_val,'footer-logo').'</a></div>';
        $output .= $this->widget_after(); // render widget after content

        return $output;
    }

    public function widget_title()
    {
        //  Implement widget_title() method.
        return __('Image');
    }
}