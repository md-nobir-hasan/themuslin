<?php


namespace App\Helpers;


class SidebarMenuHelper
{
    public function render_sidebar_menus(): string
    {
        $dd = new \App\Helpers\DashboardMenu\MenuWithPermission();
        $dd->add_menu_item('dashboard-menu', [
            'route' => route('admin.home'),
            'label' => __('Dashbaord'),
            'parent' => null,
            'class' => 'hello',
            'permissions' => ['create-list', 'hello'],
            'icon' => 'ti ti-pencil',
        ]);

        $dd->add_menu_item('admin-list', [
            'route' => route('admin.home'),
            'label' => __('Admin List'),
            'parent' => 'dashboard-menu',
            'permissions' => ['create-list', 'hello'],
            'icon' => 'ti ti-pencil',
        ]);
        $dd->add_menu_item('admin-list-two', [
            'route' => route('admin.home'),
            'label' => __('Admin List 02'),
            'parent' => 'dashboard-menu',
            'permissions' => ['create-list', 'hello'],
            'icon' => 'ti ti-pencil',
        ]);

        $dd->add_menu_item('admin-list-three', [
            'route' => route('admin.home'),
            'label' => __('Admin List 03'),
            'parent' => 'dashboard-menu',
            'permissions' => ['create-list', 'hello'],
            'icon' => 'ti ti-pencil',
        ]);

        /* group two */

        $dd->add_menu_item('dashboard-menu-two', [
            'route' => route('admin.home'),
            'label' => __('Dashbaord Two'),
            'parent' => null,
            'class' => 'hello',
            'permissions' => ['create-list', 'hello'],
            'icon' => 'ti ti-pencil',
        ]);

        $dd->add_menu_item('admin-list-four', [
            'route' => route('admin.home'),
            'label' => __('Admin List 04'),
            'parent' => 'dashboard-menu-two',
            'permissions' => ['create-list', 'hello'],
            'icon' => 'ti ti-pencil',
        ]);
        $dd->add_menu_item('admin-list-five', [
            'route' => route('admin.home'),
            'label' => __('Admin List 05'),
            'parent' => 'dashboard-menu-two',
            'permissions' => ['create-list', 'hello'],
            'icon' => 'ti ti-pencil',
        ]);

        /* three */

        $dd->add_menu_item('admin-list-nested', [
            'route' => route('admin.home'),
            'label' => __('Admin List 06'),
            'parent' => 'admin-list-five',
            'permissions' => ['create-list', 'hello'],
            'icon' => 'ti ti-pencil',
        ]);

        /* four */
        $dd->add_menu_item('dashboard-menu-three', [
            'route' => route('admin.home'),
            'label' => __('Dashbaord three'),
            'parent' => null,
            'class' => 'hello',
            'permissions' => ['create-list', 'hello'],
            'icon' => 'ti ti-pencil',
        ]);

        return '<ul id="dashboard_menu" class="metismenu" >' . $dd->render_menu_items() . '</ul>';
    }
}