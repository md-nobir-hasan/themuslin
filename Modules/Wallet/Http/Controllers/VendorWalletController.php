<?php

namespace Modules\Wallet\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\SubOrder;
use Modules\Vendor\Http\Services\VendorServices;
use Modules\Wallet\Entities\VendorWalletGateway;
use Modules\Wallet\Entities\VendorWalletGatewaySetting;
use Modules\Wallet\Entities\VendorWithdrawRequest;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletHistory;
use Modules\Wallet\Http\Requests\VendorHandleWithdrawRequest;

class VendorWalletController extends Controller
{
    public function index(){
        $data = VendorServices::vendorAccountBanner();

        return view("wallet::vendor.index", $data);
    }

    public function withdraw(){
        $wallet = Wallet::where("vendor_id", auth()->guard("vendor")->id())->first();
        // first og all get all list of payment gateway that is created bu admin
        $adminGateways = VendorWalletGateway::where("status_id",1)->get();
        $savedGateway = VendorWalletGatewaySetting::where(["vendor_id" => auth("vendor")->id()])->first();

        $data = [
            "total_order_amount" => (double) SubOrder::where("vendor_id", auth()->guard("vendor")->id())->sum("total_amount"),
            "total_complete_order_amount" => (double) SubOrder::where("vendor_id", auth()->guard("vendor")->id())->where("order_status", "complete")->whereHas("orderTrack", function ($orderTrack){
                $orderTrack->where("name", "delivered");
            })->sum("total_amount"),
            "pending_balance" => $wallet->pending_balance,
            "current_balance" => $wallet->balance,
            "adminGateways" => $adminGateways,
            "savedGateway" => $savedGateway,
        ];

        return view("wallet::vendor.wallet-withdraw", $data);
    }

    public function withdrawRequestPage(){
        $withdrawRequests = VendorWithdrawRequest::with("gateway")
            ->where("vendor_id", auth("vendor")->id())
            ->whereHas("gateway")
            ->orderByDesc("id")->paginate(10);

        return view("wallet::vendor.wallet-request",compact("withdrawRequests"));
    }

    public function handleWithdraw(VendorHandleWithdrawRequest $request){
        $data = $request->validated();
        $wallet = Wallet::where("vendor_id", $data["vendor_id"])->first();

        if($wallet->balance >= $data["amount"]){
            $withdraw = VendorWithdrawRequest::create($data);

            return back()->with([
                "type" => $withdraw ? "success" : "danger",
                "success" => (bool) $withdraw ?? false,
                "msg" => $withdraw ? "Successfully sent your request" : "Failed to send request"
            ]);
        }

        return back()->with(["success" => false,"type" => "danger", "msg" => "Your requested amount is greater than your available balance"]);
    }

    public function walletHistory(){
        $histories = WalletHistory::where("vendor_id", auth('vendor')->id())
            ->orderByDesc('id')->paginate(20);

        return view("wallet::vendor.wallet-history", compact('histories'));
    }
}
