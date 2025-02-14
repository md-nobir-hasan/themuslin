<?php

namespace Modules\Wallet\Http\Controllers;

use App\Helpers\SanitizeInput;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Wallet\Entities\VendorWalletGateway;
use Modules\Wallet\Entities\VendorWalletGatewaySetting;

class VendorWalletGatewaySettingController extends Controller
{
    public function index()
    {
        // first og all get all list of payment gateway that is created bu admin
        $adminGateways = VendorWalletGateway::where("status_id",1)->get();
        $savedGateway = VendorWalletGatewaySetting::where(["vendor_id" => auth("vendor")->id()])->first();

        return view("wallet::vendor.withdraw-gateway-settings", ["adminGateways" => $adminGateways,"savedGateway" => $savedGateway]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            "gateway_name" => "required",
            "gateway_filed" => "sometimes|string",
            "gateway_filed.*" => "sometimes|string"
        ]);

        foreach($data['gateway_filed'] as $key => $value){
            $data['gateway_filed'][$key] = SanitizeInput::esc_html($value);
        }

        $walletGatewaySettings = VendorWalletGatewaySetting::updateOrCreate([
            "vendor_id" => auth("vendor")->id()
        ],[
            "vendor_id" => auth("vendor")->id(),
            "vendor_wallet_gateway_id" => $data["gateway_name"],
            "fileds" => serialize($data['gateway_filed'])
        ]);

        return back()->with([
            "success" => (bool) $walletGatewaySettings ?? false,
            "type" => $walletGatewaySettings ? "success" : "danger",
            "msg" => $walletGatewaySettings ? __("Successfully updated wallet settings") : __("Failed to update wallet settings")
        ]);
    }
}