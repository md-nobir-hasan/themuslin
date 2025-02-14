<?php


namespace App\WidgetsBuilder\Widgets;

use App\Helpers\SanitizeInput;
use App\Language;
use App\Menu;
use App\WidgetsBuilder\WidgetBase;

class NavigationMenuWidget extends WidgetBase
{

    public function admin_render()
    {
        //  Implement admin_render() method.
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();


            $widget_title =  $widget_saved_values['widget_title'] ?? '';
            $selected_menu_id = $widget_saved_values['menu_id'] ?? '';

            $output .= '<div class="form-group"><input type="text" name="widget_title" class="form-control" placeholder="' . __('Widget Title') . '" value="'. $widget_title .'"></div>';

            $output .= '<div class="form-group">';
            $output .= '<select class="form-control" name="menu_id">';

            $navigation_menus = Menu::all();

            foreach($navigation_menus as $menu_item){
                $selected = $selected_menu_id == $menu_item->id ? 'selected' : '';
                $output .= '<option value="'.$menu_item->id.'" '.$selected.'>'.$menu_item->title.'</option>';
            }
            $output .= '</select>';
            $output .= '</div>';



        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        //  Implement frontend_render() method.
        $widget_saved_values = $this->get_settings();
        $widget_title =  SanitizeInput::esc_html($widget_saved_values['widget_title'] ?? '');
        $menu_id = $widget_saved_values['menu_id'] ?? '';

        $output = $this->widget_before(); //render widget before content
        $output .= "<div class='footer-widget widget'>";
        if (!empty($widget_title)){
            $output .= '<h6 class="widget-title">'.$widget_title.'</h6>';
        }
        $output .= "<div class='footer-inner mt-4'>";
        $output .= '<ul class="footer-link-list">';
        $output .= render_frontend_menu($menu_id);
        $output .= '</ul>';
        $output .= '</div>';
        $output .= '</div>';

        $output .= $this->widget_after(); // render widget after content

        return $output;
    }

    public function widget_title()
    {
        //  Implement widget_title() method.
        return __('Navigation Menu');
    }
}