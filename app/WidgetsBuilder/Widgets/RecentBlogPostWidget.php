<?php


namespace App\WidgetsBuilder\Widgets;

use App\Blog;
use App\Language;
use App\Menu;
use App\WidgetsBuilder\WidgetBase;

class RecentBlogPostWidget extends WidgetBase
{

    /**
     * @inheritDoc
     */
    public function admin_render()
    {
        //  Implement admin_render() method.
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $widget_title = $widget_saved_values['widget_title'] ?? '';
        $output .= '<div class="form-group"><input type="text" name="widget_title" class="form-control" placeholder="' . __('Widget Title') . '" value="' . $widget_title . '"></div>';

        $post_items = $widget_saved_values['post_items'] ?? '';
        $output .= '<div class="form-group"><input type="text" name="post_items" class="form-control" placeholder="' . __('Post Items') . '" value="' . $post_items . '"></div>';

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function frontend_render()
    {
        //  Implement frontend_render() method.
        $widget_saved_values = $this->get_settings();

        $widget_title = $widget_saved_values['widget_title'] ?? '';
        $post_items = $widget_saved_values['post_items'] ?? '';

        $blog_posts = Blog::where(['status' => 'publish'])->orderBy('id', 'DESC')->take($post_items)->get();

        $output = $this->widget_before('widget-recently-added'); //render widget before content

        if (!empty($widget_title)) {
            $output .= '<h5 class="widget-title">' . purify_html($widget_title) . '</h5>';
        }

        $output .= '<div class="recently-added-wrap">';

        foreach ($blog_posts as $post) {
            $output .=
                '<div class="single-recent-item">
                    <div class="img-box">' . render_image_markup_by_attachment_id($post->image, '', 'thumb') . '</div>
                    <div class="content">
                        <h5 class="title">
                            <a href="' . route('frontend.blog.single', $post->slug) . '">' . purify_html($post->title) . '</a>
                        </h5>
                        <a href="' . route('frontend.blog.single', $post->slug) . '">
                            <span class="product-meta">' . date_format($post->created_at, 'D M Y') . '</span>
                        </a>
                    </div>
                </div>';
        }
        $output .= '</div>';

        $output .= $this->widget_after(); // render widget after content

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function widget_title()
    {
        //  Implement widget_title() method.
        return __('Recent Blog Post');
    }
}