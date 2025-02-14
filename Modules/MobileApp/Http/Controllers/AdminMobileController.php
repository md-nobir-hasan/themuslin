<?php

namespace Modules\MobileApp\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Routing\Controller;
use App\Page;
use Illuminate\Http\Request;

class AdminMobileController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:admin");
    }

    public function terms_and_condition(){
        $pages = Page::select("id","title")->get();

        return view("mobileapp::mobile-controller.terms_and_condition", compact("pages"));
    }

    public function update_terms_and_condition(Request $request){
        update_static_option("mobile_terms_and_condition", $request->page);

        return redirect()->back()->with(FlashMsg::update_succeed("Terms and condition"));
    }

    public function privacy_and_policy(){
        $pages = Page::select("id","title")->get();

        return view("mobileapp::mobile-controller.privacy_policy", compact("pages"));
    }

    public function update_privacy_and_policy(Request $request){
        update_static_option("mobile_privacy_and_policy", $request->page);

        return redirect()->back()->with(FlashMsg::update_succeed("Privacy and policy"));
    }

    public function buyerAppSettings(){
        return view("mobileapp::mobile-controller.buyer_app_settings");
    }

    public function updateBuyerAppSettings(Request $request){
        update_static_option("app_secret_key", $request->app_secret_key);

        return redirect()->back()->with(FlashMsg::update_succeed("Buyer app settings"));
    }
}
