<?php

namespace Modules\Vendor\Http\Controllers;

use App\Mail\BasicMail;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\Campaign\Entities\Campaign;
use Modules\Order\Entities\SubOrder;
use Modules\Product\Entities\Product;
use Modules\User\Entities\User;
use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Http\Services\VendorServices;
use Modules\Wallet\Entities\Wallet;

class VendorController extends Controller
{
    public function adminIndex(){
        return "Admin Index method rendered";
    }

    public function index(){
        $data = VendorServices::vendorAccountBanner();

        $vendor_id = auth("vendor")->id();
        $data["total_product"] = Product::where("vendor_id",$vendor_id)->count() ?? 0;
        $data["totalCampaign"] = Campaign::where("vendor_id", $vendor_id)->count() ?? 0;
        $data["totalOrder"] = SubOrder::where("vendor_id", $vendor_id)->count() ?? 0;
        $data["successOrder"] = SubOrder::where("vendor_id", $vendor_id)->whereHas("order", function ($orderQuery){
            $orderQuery->where("order_status", "complete");
        })->count() ?? 0;



        $running_month_earning = SubOrder::where('vendor_id', $vendor_id)
            ->selectRaw("DATE_FORMAT(sub_orders.created_at,'%e') as date, IFNULL(SUM(total_amount), 0) as amount")
            ->whereBetween('sub_orders.created_at', [
                Carbon::now()->startOfMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->addDay(1)->format('Y-m-d')
            ])->whereHas('orderTrack', function ($query){
                $query->where('name', 'delivered');
            })
            ->groupBy('date')->get()->sum('amount');

        $last_month_earning = SubOrder::where('vendor_id', $vendor_id)
            ->selectRaw("DATE_FORMAT(sub_orders.created_at,'%e') as date, IFNULL(SUM(total_amount), 0) as amount")
            ->whereBetween('sub_orders.created_at', [
                Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d'),
                Carbon::now()->subMonth(1)->endOfMonth()->addDay(1)->format('Y-m-d')
            ])->whereHas('orderTrack', function ($query){
                $query->where('name', 'delivered');
            })
            ->groupBy('date')->get()->sum('amount');

        $this_year_earning = SubOrder::where('vendor_id', $vendor_id)
            ->selectRaw("DATE_FORMAT(sub_orders.created_at,'%e') as date, IFNULL(SUM(total_amount), 0) as amount")
            ->whereBetween('sub_orders.created_at', [
                Carbon::now()->startOfYear()->format('Y-m-d'),
                Carbon::now()->endOfYear()->addDay(1)->format('Y-m-d')
            ])->whereHas('orderTrack', function ($query){
                $query->where('name', 'delivered');
            })
            ->groupBy('date')->get()->sum('amount');

        $running_week_earning = SubOrder::where('vendor_id', $vendor_id)
            ->selectRaw("DATE_FORMAT(sub_orders.created_at,'%a') as date, IFNULL(SUM(total_amount), 0) as amount")
            ->whereBetween('sub_orders.created_at', [
                Carbon::now()->startOfWeek()->format('Y-m-d'),
                Carbon::now()->endOfWeek()->addDay(1)->format('Y-m-d')
            ])->whereHas('orderTrack', function ($query){
                $query->where('name', 'delivered');
            })
            ->groupBy('date')->get()->sum('amount');

        $data += [
            'running_month_earning' => $running_month_earning,
            'last_week_earning' => $running_week_earning,
            'last_month_earning' => $last_month_earning,
            'this_year_earning' => $this_year_earning,
        ];

        return view("vendor::vendor.home.index", $data);
    }


    public function user_email_verify_index()
    {
        $user_details = Auth::guard('vendor')->user();

        if ($user_details->email_verified == 1) {
            return redirect()->route('vendor.home');
        }

        if (empty($user_details->email_verify_token)) {
            Vendor::find($user_details->id)->update(['email_verify_token' => \Str::random(8)]);

            $user_details = Vendor::find($user_details->id);
            $message_body = __('Here is your verification code') . ' <span class="verify-code">' . $user_details->email_verify_token . '</span>';

            try {
                Mail::to($user_details->email)->send(new BasicMail([
                    'subject' => __('Verify your email address'),
                    'message' => $message_body
                ]));
            } catch (\Exception $e) {
                //
            }
        }

        return view('vendor::vendor.vendor.email-verify');
    }

    public function reset_user_email_verify_code()
    {
        $user_details = Auth::guard('vendor')->user();
        if ($user_details->email_verified == 1) {
            return redirect()->route('vendor.home');
        }

        $message_body = __('Here is your verification code') . ' <span class="verify-code">' . $user_details->email_verify_token . '</span>';

        try {
            Mail::to($user_details->email)->send(new BasicMail([
                'subject' => __('Verify your email address'),
                'message' => $message_body
            ]));
        } catch (\Exception $e) {
            return redirect()->route('vendor.email.verify')->with(['msg' => $e->getMessage(), 'type' => 'danger']);
        }

        return redirect()->route('vendor.email.verify')->with(['msg' => __('Resend Verify Email Success'), 'type' => 'success']);
    }

    public function user_email_verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required'
        ], [
            'verification_code.required' => __('verify code is required')
        ]);

        $user_details = Auth::guard('vendor')->user();
        $user_info = Vendor::where(['id' => $user_details->id, 'email_verify_token' => $request->verification_code])->first();

        if (empty($user_info)) {
            return redirect()->back()->with(['msg' => __('your verification code is wrong, try again'), 'type' => 'danger']);
        }

        $user_info->email_verified = 1;
        $user_info->save();

        return redirect()->route('vendor.home');
    }
}
