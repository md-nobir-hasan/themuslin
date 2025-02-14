<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class SiteSettingsController extends Controller
{
    public function shippingMethods(){
        // return view for shipping methods settings
        return view("backend.site-settings.shipping-charge");
    }

    public function updateShippingMethods(Request $request){
        // first validate all requested data hare
        $validatedData = $request->validate([
            "shipping_charge_type" => "required|string",
            "global_shipping_charge_amount" => "nullable",
        ]);

        // update only validated item
        update_static_option("shipping_charge_type", $validatedData["shipping_charge_type"]);
        update_static_option("global_shipping_charge_amount", $validatedData["shipping_charge_type"] == "vendor" ? '' :  $validatedData["global_shipping_charge_amount"]);


        return back()->with(["msg" => __("Shipping Charge Updated successfully")]);
    }
}
