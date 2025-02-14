<?php


namespace App\WidgetsBuilder\Widgets;


use App\Language;
use App\WidgetsBuilder\WidgetBase;

class BlogSearchWidget extends WidgetBase
{

    public function admin_render()
    {
        //  Implement admin_render() method.
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();


    $widget_title = $widget_saved_values['widget_title'] ?? '';
    $output .= '<div class="form-group"><input type="text" name="widget_title_" class="form-control" placeholder="' . __('Widget Title') . '" value="' . $widget_title . '"></div>';

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        //  Implement frontend_render() method.
        $widget_saved_values = $this->get_settings();

        $widget_title = $widget_saved_values['widget_title'] ?? '';

        $output = $this->widget_before('widget-search'); //render widget before content

        if (!empty($widget_title)) {
            $output .= '<h5 class="widget-title">' . purify_html($widget_title) . '</h5>';
        }

        $output .=  '<form action="'.route('frontend.blog.search').'" method="get" class="search-from">
                        <div class="form-group">
                            <input type="search" class="form-control" name="search" placeholder="'.__('Search...').'">
                        </div>
                        <button type="submit" class="widget-search-btn"><i class="las la-search icon"></i></button>
                    </form>';

        $output .= $this->widget_after(); // render widget after content

        return $output;
    }

    public function widget_title()
    {
        //  Implement widget_title() method.
        return __('Blog Search');
    }
}
