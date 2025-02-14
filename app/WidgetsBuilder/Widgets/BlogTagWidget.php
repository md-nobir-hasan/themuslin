<?php


namespace App\WidgetsBuilder\Widgets;


use App\BlogCategory;
use App\EventsCategory;
use App\Language;
use App\WidgetsBuilder\WidgetBase;
use Illuminate\Support\Str;

class BlogTagWidget extends WidgetBase
{

    public function admin_render()
    {
        //  Implement admin_render() method.
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $widget_title = $widget_saved_values['widget_title'] ?? '';
        $output .= '<div class="form-group"><input type="text" name="widget_title" class="form-control" placeholder="' . __('Widget Title') . '" value="' . $widget_title . '"></div>';

        $tag_items = $widget_saved_values['tag_items'] ?? '';
        $output .= '<div class="form-group"><input type="number" name="tag_items" class="form-control" placeholder="' . __('Post Items') . '" value="' . $tag_items . '"></div>';

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
        $tag_items = $widget_saved_values['tag_items'] ?? ''; // ======================= @todo ================================

        $blog_tags = getAllBlogTags();

        $line_limit_class = '';
        if (!empty($tag_items) && count($blog_tags) > $tag_items) {
            $line_limit_class = 'line-limit-3';
        }

        $output = $this->widget_before("widget-tag"); //render widget before content

        if (!empty($widget_title)) {
            $output .= '<h5 class="widget-title">' . purify_html($widget_title) . '</h5>';
        }

        $output .= '<div class="tag-wrap">';
        foreach ($blog_tags as $tag) {
            $output .= '<a href="'.route('frontend.blog.tags.page', ['name' => $tag]).'" class="tag-btn">'.purify_html($tag).'</a>';
        }
        $output .= '</div>';

        $output .= $this->widget_after(); // render widget after content

        return $output;
    }

    public function widget_title()
    {
        //  Implement widget_title() method.
        return __('Blog Tag');
    }
}
