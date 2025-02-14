<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Order\Entities\SubOrder;
use Modules\Vendor\Http\Resources\AdminWalletGatewayResource;
use Modules\Vendor\Http\Resources\WithdrawRequestListResource;
use Modules\Vendor\Http\Services\VendorServices;
use Modules\Wallet\Entities\VendorWalletGateway;
use Modules\Wallet\Entities\VendorWalletGatewaySetting;
use Modules\Wallet\Entities\VendorWithdrawRequest;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Http\Requests\VendorHandleWithdrawRequest;

class VendorWalletApiController extends Controller
{
    public function index(){
        $data = VendorServices::vendorAccountBanner('api');

        return $data;
    }

    public function withdraw(){
        $wallet = Wallet::where("vendor_id", auth()->guard("sanctum")->id())->first();
        // first og all get all list of payment gateway that is created bu admin
        $adminGateways = VendorWalletGateway::where("status_id",1)->get();
        $savedGateway = VendorWalletGatewaySetting::where(["vendor_id" => auth("sanctum")->id()])->first();

        if($savedGateway){
            $savedGateway->fileds = $savedGateway?->fileds ? unserialize($savedGateway?->fileds) : null;
        }

        return [
            "total_order_amount" => (double) SubOrder::where("vendor_id", auth()->guard("sanctum")->id())->sum("total_amount"),
            "total_complete_order_amount" => (double) SubOrder::where("vendor_id", auth()->guard("sanctum")->id())->where("order_status", "complete")->whereHas("orderTrack", function ($orderTrack){
                $orderTrack->where("name", "delivered");
            })->sum("total_amount"),
            "pending_balance" => $wallet->pending_balance,
            "current_balance" => $wallet->balance,
            "adminGateways" => AdminWalletGatewayResource::collection($adminGateways),
            "savedGateway" => $savedGateway,
        ];
    }

    public function withdrawRequestPage(){
        $withdrawRequests = VendorWithdrawRequest::with("gateway")
            ->where("vendor_id", auth("sanctum")->id())
            ->orderByDesc("id")->paginate(10);

        return WithdrawRequestListResource::collection($withdrawRequests);
    }

    public function handleWithdraw(VendorHandleWithdrawRequest $request){
        $data = $request->validated();
        $wallet = Wallet::where("vendor_id", $data["vendor_id"])->first();

        if($wallet->balance >= $data["amount"]){
            $withdraw = VendorWithdrawRequest::create($data);

            return ["success" => (bool) $withdraw ?? false,"msg" => $withdraw ? "Successfully sent your request" : "Failed to send request"];
        }

        return ["success" => false,"type" => "danger", "msg" => "Your requested amount is greater than your available balance"];
    }
}