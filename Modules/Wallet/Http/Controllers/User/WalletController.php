<?php

namespace Modules\Wallet\Http\Controllers\User;

use App\Helpers\PaymentGatewayCredential;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletHistory;

class WalletController extends Controller
{
    private const CANCEL_ROUTE = 'user-home.wallet.deposit.payment.cancel.static';

    public function deposit_payment_cancel_static()
    {
        return view('wallet::user.wallet.cancel');
    }
    //todo:display wallet history
    public function wallet_history()
    {
        $user_id = Auth::guard('web')->user()->id;
        $histories = WalletHistory::where('user_id', $user_id)->latest()->paginate(10);
        $wallet_balance = Wallet::where('user_id', $user_id)->first();
        $total_wallet_balance = $wallet_balance->balance ?? '';

        return view('wallet::frontend.user.wallet-history',compact('histories','total_wallet_balance'));
    }

    //todo: pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $user_id = Auth::guard('web')->user()->id;
            $all_histories = WalletHistory::where('user_id',$user_id)->latest()->paginate(10);
            return view('wallet::freelancer.wallet.search-result', compact('all_histories'))->render();
        }
    }

    //todo: search history
    public function search_history(Request $request)
    {
        $all_histories = WalletHistory::where('user_id',Auth::guard('web')->user()->id)->where('created_at', 'LIKE', "%". strip_tags($request->string_search) ."%")
            ->paginate(10);
        if($all_histories->total() >= 1){
            return view('wallet::freelancer.wallet.search-result', compact('all_histories'))->render();
        }else{
            return response()->json([
                'status'=>__('nothing')
            ]);
        }
    }


    //todo:deposit balance to wallet
    public function deposit(Request $request)
    {
        $request->validate([
            'amount'=>'required|numeric|gt:0',
        ]);

        if($request->selected_payment_gateway === 'manual_payment') {
            $request->validate([
                'manual_payment_image' => 'required|mimes:jpg,jpeg,png,pdf'
            ]);
        }

        //todo:: deposit amount
        $user = Auth::guard('web')->user();
        $user_id = $user->id;
        session()->put('user_id',$user_id);
        $total = $request->amount;
        $name = $user->first_name.' '.$user->last_name;
        $email = $user->email;
        $user_type = 'Customer';

        $wallet = Wallet::where('user_id',$user_id)->first();
        if(empty($wallet)){
            $wallet = Wallet::create([
                'user_id' => $user_id,
                'balance' => 0,
                'pending_balance' => 0,
                'status' => 1,
            ]);
        }

        $deposit = WalletHistory::create([
            'wallet_id' => $wallet->id,
            'user_id' => $user_id,
            'amount' => $total,
            'payment_gateway' => $request->selected_payment_gateway,
            'payment_status' => 'pending',
            'type' => 5,// this one mean's incoming
        ]);

        $last_deposit_id = $deposit->id;
        $title = __('Deposit To Wallet');
        $description = sprintf(__('Order id #%1$d Email: %2$s, Name: %3$s'),$last_deposit_id,$email,$name);

        if($request->selected_payment_gateway === 'manual_payment') {
            if($request->hasFile('manual_payment_image')){
                $manual_payment_image = $request->manual_payment_image;
                $img_ext = $manual_payment_image->extension();

                $manual_payment_image_name = 'manual_attachment_'.time().'.'.$img_ext;
                if(in_array($img_ext,['jpg','jpeg','png','pdf'])){
                    $manual_image_path = 'assets/uploads/manual-payment/';
                    $manual_payment_image->move($manual_image_path,$manual_payment_image_name);
                    WalletHistory::where('id',$last_deposit_id)->update([
                        'manual_payment_image'=>$manual_payment_image_name
                    ]);
                }else{
                    return back()->with(toastr_warning(__('Image type not supported')));
                }
            }

            try {
                $message_body = __('Hello a ') .$user_type. __(' just deposit to his wallet. Please check and confirm').'</br>'.'<span class="verify-code">'.__('Deposit ID: ').$last_deposit_id.'</span>';
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => __('Deposit Confirmation'),
                    'message' => $message_body
                ]));

                Mail::to($email)->send(new BasicMail([
                    'subject' => __('Deposit Confirmation'),
                    'message' => __('Manual deposit success. Your wallet will credited after admin approval').'</br>'.'<span class="verify-code">'.__('Deposit ID: ').$last_deposit_id.'</span>'
                ]));
            } catch (\Exception $e) {
                //
            }
            toastr_success('Manual deposit success. Your wallet will credited after admin approval');
            return back();

        }else{
            $gateway_function = 'get_' . $request->selected_payment_gateway . '_credential';
            $gateway = PaymentGatewayCredential::$gateway_function();

            return $gateway->charge_customer(
                $this->buildPaymentArg($total,$title,$description,$last_deposit_id,$email,$name, route('user-home.wallet.'. $request->selected_payment_gateway .'.ipn.wallet'))
            );
        }
    }

    private function buildPaymentArg($total,$title,$description,$last_deposit_id,$email,$name,$ipn_route)
    {
        return [
            'amount' => $total,
            'title' => $title,
            'description' => $description,
            'ipn_url' => $ipn_route,
            'order_id' => $last_deposit_id,
            'track' => \Str::random(36),
            'cancel_url' => route(self::CANCEL_ROUTE,$last_deposit_id),
            'success_url' => route('user-home.wallet.history'),
            'email' => $email,
            'name' => $name,
            'payment_type' => 'deposit',
        ];
    }
}
