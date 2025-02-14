<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NavbarController extends Controller
{
    public function navbar_settings()
    {
        return view('backend.pages.navbar-settings');
    }

    public function update_navbar_settings(Request $request)
    {

        $request->validate([
            'home_page_navbar_button_status' => 'nullable',
            'home_page_navbar_button_url' => 'nullable',
            'home_page_navbar_button_icon' => 'nullable',
        ]);

        $request->validate([
            'home_page_navbar_button_subtitle' => 'nullable|string',
            'home_page_navbar_button_title' => 'nullable|string',
        ]);

        //save repeater values
        $all_fields = [
            'home_page_navbar_button_subtitle',
            'home_page_navbar_button_title',
            'home_page_navbar_button_url',
            'home_page_navbar_button_status',
            'home_page_navbar_button_icon',
        ];
        foreach ($all_fields as $field) {
            update_static_option($field, $request->$field);
        }

        return redirect()->back()->with(FlashMsg::settings_update());
    }
}
