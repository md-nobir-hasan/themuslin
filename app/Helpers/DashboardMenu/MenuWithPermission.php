<?php


namespace App\Helpers\DashboardMenu;


class MenuWithPermission
{
    private array $parents = [];
    private array $menus_items = [];

    public function add_menu_item(string $id, array $args = [])
    {
        if (empty($args)) {
            return;
        }

        $this->menus_items[$id] = [
            'route' => $args['route'],
            'label' =>  $args['label'],
            'parent' => $args['parent'],
            'class' =>  $args['class'] ?? '',
            'permissions' => $args['permissions'],
            'icon' => $args['icon'],
        ];
        if (isset($args['parent']) && !empty($args['parent'])) {
            $this->parents[] = $args['parent'];
        }
    }

    public function render_menu_items(): string
    {
        $output = '';
        $all_menu_items = $this->menus_items;

        foreach ($all_menu_items as $id => $item) {
            if (!$this->has_permission_to_view($item)) continue;
            if (!$this->has_route($item) && !$this->has_sub_menu_item($id)) {
                abort(405, 'Route name required to render menu');
            }
            if ($this->is_submenu_item($item) ){
               continue;
            }

            $output .= '<li';
            $li_classes = [];
            if ($this->is_active_menu_item($item)) {
                $li_classes[] = 'active';
            }
            $route = $item['route'];
            if ($this->has_sub_menu_item($id)) {
                $li_classes[] = 'main_dropdown';
                $route = '#1';
            }
            $output.= ' class="'.implode(' ',$li_classes).'"';
            $output .= '>';

            $output .= '<a href="' . $route. ' " aria-expanded="false">';
            if ($this->has_icon($item)) {
                $output .= '<i class="' . $item['icon'] . '"></i>';
            }

            if ($this->has_label($item)) {
                $output .= '<span>' . $item['label'] . 'parent</span>';
            }
            $output .= '</a>';
            if ($this->has_sub_menu_item($id)) {
                $output = $this->render_sub_menus($id, $output);
            }
            $output .= '</li>';
        }

        return $output;
    }

    private function render_sub_menus($id, &$output): string
    {
        $all_menu_items = $this->get_all_submenu_by_parent_id($id);
        $submenu_item = '';
        $submenu_item .= '<ul class="collapse">';
        foreach ($all_menu_items as $submenu_id => $sub_menu) {
            $submenu_item .= $this->render_single_submenu_item($submenu_id, $sub_menu);
        }
        $submenu_item .= '</ul>';
        $output .= $submenu_item;
        return $output;
    }

    private function is_active_menu_item($items): bool
    {
        $route_name = $items['route'];
        if (!$this->has_route($route_name)) {
            return false;
        }
        return (bool)request()->routeIs($route_name);
    }

    private function has_permission_to_view($items): bool
    {
        $permissions = $items['permissions'];
        if (isset($permissions) && !empty($permissions)) {
            return true;
        }

        $admin_details = auth('admin')->user();
        switch ($permissions) {
            case(is_array($permissions)):
                return $admin_details->canany($permissions);
                break;
            case(is_string($permissions)):
                return $admin_details->can($permissions);
                break;
            default:
                return false;
                break;
        }
    }

    private function has_route($item): bool
    {
        return isset($item['route']) && !empty($item['route']);
    }

    private function has_icon($item): bool
    {
        return isset($item['icon']) && !empty($item['icon']);
    }

    private function has_label($item): bool
    {
        return isset($item['label']) && !empty($item['label']);
    }

    private function has_sub_menu_item($id): bool
    {
        return in_array($id,$this->parents);
    }

    private function get_all_submenu_by_parent_id($id): array
    {
        $all_menu_items = $this->menus_items;
        $all_submenu_items = [];
        foreach ($all_menu_items as $item_id => $item) {
            if (isset($item['parent']) && $item['parent'] === $id) {
                $all_submenu_items[$item_id] = $item;
            }
        }
        return $all_submenu_items;
    }

    private function render_single_submenu_item($submenu_id, $sub_menu): string
    {

        if (!$this->has_permission_to_view($sub_menu)) {
            return '';
        }
        if (!$this->has_route($sub_menu)) {
            abort(405, 'Route name required to render submenu');
        }
        if (!$this->is_submenu_item($sub_menu)){
            return '';
        }

        $output = '<li';
        $li_classes = [];
        if ($this->is_active_menu_item($sub_menu)) {
            $li_classes[] = 'active';
        }
        $route = $sub_menu['route'];
        if ($this->has_sub_menu_item($submenu_id)) {
            $li_classes[] = 'main_dropdown';
            $route = '#1';
        }
        $output.= ' class="'.implode(' ',$li_classes).'"';
        $output .= '>';

        $output .= '<a href="' . $route. ' " aria-expanded="false">';

        if ($this->has_icon($sub_menu)) {
            $output .= '<i class="' . $sub_menu['icon'] . '"></i>';
        }
        if ($this->has_label($sub_menu)) {
            $output .= $sub_menu['label'].' submenu';
        }
        $output .= '</a>';
        if ($this->has_sub_menu_item($submenu_id)) {
            $output = $this->render_sub_menus($submenu_id, $output);
        }

        $output .= '</li>';


        return $output;
    }

    private function is_submenu_item($item) : bool
    {
        return isset($item['parent']) && !is_null($item['parent']);
    }
}


