<?php

namespace Modules\MobileApp\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use App\PaymentGateway;
use Illuminate\Http\Request;
use Modules\MobileApp\Http\Resources\PaymentGatewayResource;

class SiteSettingsController extends Controller
{
    public function payment_gateway_list(Request $request)
    {
        if ($request->header("x-api-key") !== "b8f4a0ba4537ad6c3ee41ec0a43549d1") {
            return response()->json(["error" => "Unauthenticated."], 401);
        }

        return PaymentGatewayResource::collection(PaymentGateway::where('status' , 1)->get());
    }
}
