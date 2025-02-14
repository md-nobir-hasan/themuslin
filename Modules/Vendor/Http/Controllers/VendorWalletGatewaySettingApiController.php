<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Vendor\Http\Resources\AdminWalletGatewayResource;
use Modules\Wallet\Entities\VendorWalletGateway;
use Modules\Wallet\Entities\VendorWalletGatewaySetting;

class VendorWalletGatewaySettingApiController extends Controller
{
    public function index()
    {
        // first og all get all list of payment gateway that is created bu admin
        $adminGateways = VendorWalletGateway::where("status_id",1)->get();
        $savedGateway = VendorWalletGatewaySetting::where(["vendor_id" => auth("sanctum")->id()])->first();

        if($savedGateway){
            $savedGateway->fileds = $savedGateway?->fileds ? unserialize($savedGateway?->fileds) : null;
        }

        return [
            "adminGateways" => AdminWalletGatewayResource::collection($adminGateways),
            "savedGateway" => $savedGateway
        ];
    }


    public function update(Request $request)
    {
        $data = $request->validate([
            "gateway_name" => "required",
            "gateway_filed" => "sometimes",
            "gateway_filed.*" => "sometimes"
        ]);

        $walletGatewaySettings = VendorWalletGatewaySetting::updateOrCreate([
            "vendor_id" => auth("sanctum")->id()
        ],[
            "vendor_id" => auth("sanctum")->id(),
            "vendor_wallet_gateway_id" => $data["gateway_name"],
            "fileds" => serialize($data['gateway_filed'])
        ]);

        return [
            "success" => (bool) $walletGatewaySettings ?? false ,
            "msg" => $walletGatewaySettings ? __("Successfully updated wallet settings") : __("Failed to update wallet settings")
        ];
    }
}