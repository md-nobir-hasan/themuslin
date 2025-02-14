<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductSettingsController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(): Renderable
    {
        // render settings page
        return view('product::settings');
    }

    public function updateSettings(Request $request){
        $data = $request->validate([
            "stock_threshold_amount" => "nullable",
        ]);

        foreach ($data as $key => $value){
            update_static_option($key, $value);
        }

        return back()->with([
            "msg" => __("Successfully updated settings"),
            "type" => "success"
        ]);
    }
}
