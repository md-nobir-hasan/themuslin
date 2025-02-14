<?php

namespace Modules\Wallet\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\DeliveryMan\Entities\DeliveryManWithdrawRequest;
use Modules\Wallet\Entities\VendorWalletGateway;
use Modules\Wallet\Entities\VendorWithdrawRequest;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletHistory;
use Modules\Wallet\Http\Requests\AdminUpdateWithdrawRequest;
use Modules\Wallet\Http\Services\WalletService;

class WalletController extends Controller
{
    public function wallet_lists()
    {
        $type = "user";
        $wallet_lists = Wallet::with('user')->whereNull("vendor_id")
            ->whereNull("delivery_man_id")->latest()->get(['id','user_id','balance', 'status']);

        return view('wallet::backend.wallet-lists',compact('wallet_lists','type'));
    }

    public function vendor_wallet_list()
    {
        $type = "vendor";
        $wallet_lists = Wallet::with('vendor','vendor.vendor_shop_info')->whereNull("user_id")
            ->whereNull("delivery_man_id")->latest()->get(['id','vendor_id','balance', 'status']);

        return view('wallet::backend.wallet-lists',compact('wallet_lists','type'));
    }

    public function delivery_man_wallet_list()
    {
        $type = "delivery_man";
        $wallet_lists = Wallet::with('deliveryMan')->whereNull("user_id")
            ->whereNull("vendor_id")->latest()->get(['id','delivery_man_id','balance', 'status']);

        return view('wallet::backend.wallet-lists',compact('wallet_lists','type'));
    }

    public function customer_wallet_list()
    {
        $type = "user";
        $wallet_lists = Wallet::with('user')
            ->whereNull("vendor_id")
            ->whereNull("delivery_man_id")
            ->whereHas("user")
            ->latest()
            ->get(['id','user_id','balance', 'status']);

        return view('wallet::backend.customer-wallet-lists',compact('wallet_lists','type'));
    }

    public function change_status($id)
    {
        $job = Wallet::find($id);
        $job->status === 1 ? $status = 0 : $status = 1;
        Wallet::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(FlashMsg::item_new('Status Changed Success'));
    }

    public function wallet_history()
    {
        $wallet_history_lists = WalletHistory::with('user')
            ->latest()
            ->whereHas("user")
            ->paginate(20);

        return view('wallet::backend.history',compact('wallet_history_lists'));
    }

    public function wallet_history_status($id)
    {
        $wallet_history = WalletHistory::find($id);
        $status = $wallet_history->payment_status === 'pending' ? 'complete' : '';
        WalletHistory::where('id',$id)->update(['payment_status'=>$status]);

        $wallet = Wallet::select(['id','user_id','balance'])->where('user_id',$wallet_history->user_id)->first();
        Wallet::where('user_id',$wallet->user_id)->update([
            'balance'=>$wallet->balance+$wallet_history->amount,
        ]);

        return redirect()->back()->with(FlashMsg::item_new('Status Changed Success'));
    }

    public function settings(){
        // now load blade file
        $adminGateways = VendorWalletGateway::all();

        return view("wallet::backend.settings", compact('adminGateways'));
    }

    public function settings_update(Request $request) {
        update_static_option("minimum_withdraw_amount",$request->minimum_withdraw_amount ?? 1);

        return back()->with([
            'success' => true,
            "type" => "success",
            "msg" => "Wallet settings updated successfully."
        ]);
    }

    public function withdrawRequestPage(){
        $withdrawRequests = VendorWithdrawRequest::with("gateway","vendor","wallet")
            ->whereHas("gateway")
            ->orderByDesc("id")->paginate(20);

        return view("wallet::backend.wallet-request",compact("withdrawRequests"));
    }

    public function deliveryManWithdrawRequest(){
        $withdrawRequests = DeliveryManWithdrawRequest::with("gateway","deliveryMan","wallet")
            ->orderByDesc("id")->paginate(20);

        return view("wallet::backend.delivery-man-wallet-request",compact("withdrawRequests"));
    }

    /**
     * @param AdminUpdateWithdrawRequest $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function updateDeliveryManWithdrawRequest(AdminUpdateWithdrawRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $filename = "";

        // save image into server disk
        if($file = $data["request_image"] ?? false){
            if(in_array($file->extension(),["jpeg","png","jpg","gif","svg","pdf","docx"])){
                $whereToSave = "assets/uploads/wallet-withdraw-request/";
                $filename = uniqid() . date("m-d") . rand(1111,9999) .'.'.$file->extension();
                $file->move($whereToSave, $filename);
            }
        }

        // create a page
        $data["image"] = $filename;

        if(!empty($data["request_image"])){
            unset($data["request_image"]);
        }

        $query = DeliveryManWithdrawRequest::where("id", $data["id"])->first();
        $update = $query->update($data);

        if($data["request_status"] == 'completed'){
            WalletService::updateDeliveryManWallet($query->delivery_man_id,$query->amount ,
                plus: false,
                column: 'balance'
            );
        }elseif($data["request_status"] == 'refunded'){
            WalletService::updateDeliveryManWallet($query->delivery_man_id,$query->amount ,
                column: 'balance'
            );
        }

        return back()->with(["success" => (bool) $update ?? false,
            "msg" => $update ? __("Successfully updated withdraw request.") : __("Failed to update"),
            "type" => $update ? "success" : "danger"
        ]);
    }

    public function updateWithdrawRequest(AdminUpdateWithdrawRequest $request){
        $data = $request->validated();
        $filename = "";

        // save image into server disk
        if($file = $data["request_image"] ?? false){
            if(in_array($file->extension(),["jpeg","png","jpg","gif","svg","pdf","docx"])){
                $whereToSave = "assets/uploads/wallet-withdraw-request/";
                $filename = uniqid() . date("m-d") . rand(1111,9999) .'.'.$file->extension();
                $file->move($whereToSave, $filename);
            }
        }

        // create a page
        $data["image"] = $filename;

        if(!empty($data["request_image"])){
            unset($data["request_image"]);
        }

        $query = VendorWithdrawRequest::where("id", $data["id"])->first();
        $update = $query->update($data);


        if($data["request_status"] == 'completed'){
            WalletService::updateVendorWallet($query->vendor_id,$query->amount ,
                plus: false,
                column: 'balance'
            );
        }elseif($data["request_status"] == 'refunded'){
            WalletService::updateVendorWallet($query->vendor_id,$query->amount ,
                column: 'balance'
            );
        }

        return back()->with(["success" => (bool) $query ?? false,
            "msg" => $query ? __("Successfully updated withdraw request.") : __("Failed to update"),
            "type" => $query ? "success" : "danger"
        ]);
    }

    public function history_details($id)
    {
        $history_details = WalletHistory::with('user')->where('id',$id)->first();

        return view('wallet::backend.history-details',compact('history_details'));
    }


    //todo: search category
    public function search_history(Request $request)
    {
        $all_histories = WalletHistory::where('created_at', 'LIKE', "%". strip_tags($request->string_search) ."%")
            ->paginate(10);
        if($all_histories->total() >= 1){
            return view('wallet::admin.wallet.search-result', compact('all_histories'))->render();
        }else{
            return response()->json([
                'status'=>__('nothing')
            ]);
        }
    }
}
