<?php


namespace App\WidgetsBuilder\Widgets;


use App\WidgetsBuilder\WidgetBase;

class RawHTMLWidget extends WidgetBase
{

    public function admin_render()
    {
        //  Implement admin_render() method.
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $raw_html = $widget_saved_values['raw_html'] ?? '';
        $output .= '<div class="form-group"><textarea name="raw_html" class="form-control custom_html_area" cols="30" rows="10">'.$raw_html.'</textarea></div>';

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        //  Implement frontend_render() method.
        $widget_saved_values = $this->get_settings();
        $raw_html = $widget_saved_values['raw_html'] ?? '';

        $output = $this->widget_before(); //render widget before content
        $output .= '<div class="custom-html-widget">'.$raw_html.'</div>';
        $output .= $this->widget_after(); // render widget after content

        return $output;
    }

    public function widget_title()
    {
        //  Implement widget_title() method.
        return __('Raw HTML');
    }
}