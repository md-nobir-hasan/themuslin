<?php

namespace Modules\Vendor\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Campaign\Entities\Campaign;
use Modules\DeliveryMan\Entities\DeliveryMan;
use Modules\Order\Entities\SubOrder;
use Modules\Product\Entities\Product;
use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Http\Services\VendorServices;

class VendorDashboardApiController extends Controller
{
    public function index()
    {
        $data = VendorServices::vendorAccountBanner('api');
        $vendor_id = auth("sanctum")->id();
        $data["total_product"] = Product::where("vendor_id",$vendor_id)->count() ?? 0;
        $data["totalCampaign"] = Campaign::where("vendor_id", $vendor_id)->count() ?? 0;
        $data["totalOrder"]    = SubOrder::where("vendor_id", $vendor_id)->count() ?? 0;
        $data["successOrder"]  = SubOrder::where("vendor_id", $vendor_id)->whereHas("orderTrack", function ($orderQuery){
                                    $orderQuery->where("name", "delivered");
                                 })->count() ?? 0;

        return $data;
    }

    public function updateFirebaseToken(Request $request): JsonResponse
    {
        $data = $request->validate([
            "token" => "required|string"
        ]);

        Vendor::where("id", auth()->user()->id)->update([
            "firebase_device_token" => $data["token"]
        ]);

        return response()->json([
            "msg" => __("Successfully updated firebase token."),
            "status" => true
        ]);
    }

}