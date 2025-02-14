<?php

namespace Modules\ShippingModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ShippingModule\Http\ShippingZoneServices;

class FrontendShippingController extends Controller
{
    public function shippingMethods(Request $request){
        // prepare data for send response
        $data = ShippingZoneServices::getMethods($request->id, $request->type);
        // after getting all data from shippingZoneServices send response

        return response()->json(['success' => true] + ((array) $data));
    }
}
