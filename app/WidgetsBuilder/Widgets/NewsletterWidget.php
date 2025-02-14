<?php


namespace App\WidgetsBuilder\Widgets;


use App\Events;
use App\Language;
use App\Widgets;
use App\WidgetsBuilder\WidgetBase;
use Illuminate\Support\Facades\Blade;

class NewsletterWidget extends WidgetBase
{

    public function admin_render()
    {
        //  Implement admin_render() method.
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $widget_title = $widget_saved_values['widget_title'] ?? '';
        $widget_description = $widget_saved_values['description'] ?? '';

        $output .= '<div class="form-group"><input type="text" name="widget_title" class="form-control" placeholder="' . __('Newsletter Title') . '" value="' . $widget_title . '"></div>';
        $output .= '<div class="form-group"><input type="text" name="description" class="form-control" placeholder="' . __('Newsletter Description') . '" value="' . $widget_description . '"></div>';


        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }


    public function frontend_render()
    {
        //  Implement frontend_render() method.
        $widget_saved_values = $this->get_settings();
        $widget_title = $widget_saved_values['widget_title'] ??  '';
        $description = $widget_saved_values['description'] ??  '';

        $output = $this->widget_before(); //render widget before content

        // Blade::
        $output .= '<div class="footer-widget widget">';

        if (!empty($widget_title)) {
            $output .= '<h4 class="widget-title">'.purify_html($widget_title).'</h4>';
        }

        $output .= '<div class="footer-inner mt-4">';
        if (!empty($description)) {
            $output .= '<p class="info">'.purify_html($description).'</p>';
        }

        $output .= 
        '<div class="subscribe-form">
            <form action="'.route('frontend.subscribe.newsletter').'" method="POST">
                <div class="form-message-show"></div>
                <div class="widget-form-single">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="email" name="email" class="form--control" placeholder="' . __("Your mail here") . '">
                    <button type="submit">
                        <i class="lar la-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
        </div>
        </div>';

        $output .= $this->widget_after(); // render widget after content

        return $output;

    }

    public function widget_title()
    {
        //  Implement widget_title() method.
        return __("Newsletter");
    }
}
