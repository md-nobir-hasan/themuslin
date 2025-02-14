<?php

namespace Modules\Wallet\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Wallet\Entities\VendorWalletGateway;
use Modules\Wallet\Http\Requests\StoreGatewayRequest;

class WithdrawGatewayController extends Controller
{
    public function gateway()
    {
        $gateways  = VendorWalletGateway::with("status")->get();

        return view("wallet::backend.withdraw.gateway", compact("gateways"));
    }

    public function storeGateway(StoreGatewayRequest $request){
        $data = VendorWalletGateway::create($request->validated());

        return back()->with(["status" => (bool)$data, "type" => $data ? "success" : "danger", "msg" => $data ? __("Payment Gateway created successfully.") : __("Failed to create payment gateway try again.")]);
    }


    public function updateGateway(StoreGatewayRequest $request){
        $data = $request->validated();

        $id = $data["id"];
        unset($data["id"]);

        $data = VendorWalletGateway::where("id", $id)->update($data);

        return back()->with(["status" => (bool)$data, "type" => $data ? "success" : "danger", "msg" => $data ? __("Payment Gateway updated successfully.") : __("Failed to update payment gateway try again.")]);
    }
    public function deleteGateway($id){
        return VendorWalletGateway::where("id", $id)->delete() ? "ok" : "false";
    }
}