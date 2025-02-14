<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FlashMsg;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ServicePageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function service_page_settings()
    {
        return view('backend.pages.service.service-page-settings');
    }

    public function update_service_page_settings(Request $request)
    {
        $request->validate([
            'service_page_service_items' => 'required|string|max:191',
        ]);
        update_static_option('service_page_service_items', $request->service_page_service_items);

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function service_single_page_settings()
    {
        $all_language = Language::all();

        return view('backend.pages.service.service-single-settings')->with(['all_languages' => $all_language]);
    }

    public function update_service_single_page_settings(Request $request)
    {
        $request->validate([
            'service_page_banner' => 'nullable|string',
        ]);
        update_static_option('service_page_banner', $request->service_page_banner);

        return redirect()->back()->with(FlashMsg::item_update());
    }
}
