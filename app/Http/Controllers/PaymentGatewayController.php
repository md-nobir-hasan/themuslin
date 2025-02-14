<?php

namespace App\Http\Controllers;

use App\Helpers\PaymentGatewayCredential;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderAddress;
use Modules\Order\Entities\SubOrder;
use Modules\Order\Traits\OrderTrait;
use RuntimeException;
use Modules\Wallet\Http\Services\WalletService;

class PaymentGatewayController extends Controller
{
    use  OrderTrait;

    private const CANCEL_ROUTE = 'frontend.order.payment.cancel';
    private const SUCCESS_ROUTE = 'frontend.order.payment.success';

    private float $total;
    private object $payment_details;

    protected function cancel_page(): RedirectResponse
    {
        return redirect()->route('frontend.order.payment.cancel.static');
    }

    public function order_payment_cancel(){
        return view("frontend.payment.payment-cancel");
    }

    public function order_payment_form(Request $request): RedirectResponse
    {
        $manual_transection_condition = $request->selected_payment_gateway == 'manual_payment' ? 'required' : 'nullable';
        $request_pack_id = $request->package_id;
        if(PricePlan::findOrFail($request_pack_id)->price == 0)
        {
            $request->selected_payment_gateway = 'manual_payment';
        }

        $data = $request->validate([
            'name' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'package_id' => 'required|string',
            'payment_gateway' => 'nullable|string',
            'trasaction_id' => '' . $manual_transection_condition . '',
            'trasaction_attachment' => '' . $manual_transection_condition . '|mimes:jpeg,png,jpg,gif|max:2048',
            'subdomain' => "required_if:custom_subdomain,!=,null",
            'custom_subdomain' => "required_if:subdomain,==,custom_domain__dd",
        ], [
            "custom_subdomain.required_if" => "Custom Sub Domain Required",
            "trasaction_id" => "Transaction ID Required",
            "trasaction_attachment" => "Transaction Attachment Required",
        ]);

        if ($request->custom_subdomain == null) {
            $request->validate([
                'subdomain' => 'required'
            ]);

            $existing_lifetime_plan = PaymentLogs::where(['tenant_id' => $request->subdomain, 'payment_status' => 'complete', 'expire_date' => null])->first();
            if ($existing_lifetime_plan != null) {
                return back()->with(['type' => 'danger', 'msg' => 'You are already using a lifetime plan']);
            }
        }

        if ($request->custom_subdomain != null) {
            $has_subdomain = Tenant::find(trim($request->custom_subdomain));
            if (!empty($has_subdomain)) {
                return back()->with(['type' => 'danger', 'msg' => 'This subdomain is already in use, Try something different']);
            }

            $site_domain = url('/');
            $site_domain = str_replace(['http://', 'https://'], '', $site_domain);
            $site_domain = substr($site_domain, 0, strpos($site_domain, '.'));
            $restricted_words = ['https', 'http', 'http://', 'https://','www', 'subdomain', 'domain', 'primary-domain', 'central-domain',
                'landlord', 'landlords', 'tenant', 'tenants', 'multi-store', 'multistore', 'admin',
                'user', 'user', $site_domain];

            if (in_array(trim($request->custom_subdomain), $restricted_words))
            {
                return back()->with(FlashMsg::explain('danger', 'Sorry, You can not use this subdomain'));
            }
        }

        $order_details = PricePlan::find($request->package_id) ?? '';

        $package_start_date = '';
        $package_expire_date = '';

        if (!empty($order_details)) {
            if ($order_details->type == 0) { //monthly
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = Carbon::now()->addMonth(1)->format('d-m-Y h:i:s');

            } elseif ($order_details->type == 1) { //yearly
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = Carbon::now()->addYear(1)->format('d-m-Y h:i:s');
            } else { //lifetime
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = null;
            }
        }

        if ($request->subdomain != 'custom_domain__dd') {
            $subdomain = Str::slug($request->subdomain);
        } else {
            $subdomain = Str::slug($request->custom_subdomain);
        }

        $amount_to_charge = $order_details->price;
        $this->total = $amount_to_charge;

        $custom_form_data = FormBuilder::find($request->custom_form_id) ?? '';
        $request_date_remove = $request;

        $selected_payment_gateway = $request_date_remove['selected_payment_gateway'] ?? $request_date_remove['payment_gateway'];
        if ($selected_payment_gateway == null) {
            $selected_payment_gateway = 'manual_payment';
        }

        $package_id = $request_date_remove['package_id'];
        $name = $request_date_remove['name'];
        $email = $request_date_remove['email'];
        $trasaction_id = $request_date_remove['trasaction_id'];

        if ($request->trasaction_attachment != null) {
            $image = $request->file('trasaction_attachment');
            $image_extenstion = $image->extension();
            $image_name_with_ext = $image->getClientOriginalName();

            $image_name = pathinfo($image_name_with_ext, PATHINFO_FILENAME);
            $image_name = strtolower(Str::slug($image_name));
            $image_db = $image_name . time() . '.' . $image_extenstion;

            $path = global_assets_path('assets/landlord/uploads/payment_attachments/');
            $image->move($path, $image_db);
        }
        $trasaction_attachment = $image_db ?? null;

        unset($request_date_remove['custom_form_id']);
        unset($request_date_remove['payment_gateway']);
        unset($request_date_remove['package_id']);
        unset($request_date_remove['package']);
        unset($request_date_remove['pkg_user_name']);
        unset($request_date_remove['pkg_user_email']);
        unset($request_date_remove['name']);
        unset($request_date_remove['email']);
        unset($request_date_remove['trasaction_id']);
        unset($request_date_remove['trasaction_attachment']);

        $auth = auth()->guard('web')->user();
        $auth_id = $auth->id;

        $is_tenant = Tenant::find($subdomain);

        DB::beginTransaction(); // Starting all the actions as safe translations
        try {
            // Exising Tenant + Plan
            if (!is_null($is_tenant)) {
                $old_tenant_log = PaymentLogs::where(['user_id' => $auth_id, 'tenant_id' => $is_tenant->id])->latest()->first() ?? '';

                // If Payment Renewing
                if (!empty($old_tenant_log->package_id) == $request_pack_id && !empty($old_tenant_log->user_id) && $old_tenant_log->user_id == $auth_id && $old_tenant_log->payment_status == 'complete') {
                    if ($package_expire_date != null) {
                        $old_days_left = Carbon::now()->diff($old_tenant_log->expire_date);
                        $left_days = 0;

                        if ($old_days_left->invert == 0) {
                            $left_days = $old_days_left->days;
                        }

                        $renew_left_days = 0;
                        $renew_left_days = Carbon::parse($package_expire_date)->diffInDays();

                        $sum_days = $left_days + $renew_left_days;
                        $new_package_expire_date = Carbon::today()->addDays($sum_days)->format("d-m-Y h:i:s");
                    } else {
                        $new_package_expire_date = null;
                    }

                    PaymentLogs::findOrFail($old_tenant_log->id)->update([
                        'email' => $email,
                        'name' => $name,
                        'package_name' => $order_details->title,
                        'package_price' => $amount_to_charge,
                        'package_gateway' => $selected_payment_gateway,
                        'package_id' => $package_id,
                        'user_id' => auth()->guard('web')->user()->id ?? null,
                        'tenant_id' => $subdomain ?? null,
                        'status' => 'pending',
                        'payment_status' => 'pending',
                        'renew_status' => is_null($old_tenant_log->renew_status) ? 1 : $old_tenant_log->renew_status + 1,
                        'is_renew' => 1,
                        'track' => Str::random(10) . Str::random(10),
                        'updated_at' => Carbon::now(),
                        'start_date' => $package_start_date,
                        'expire_date' => $new_package_expire_date
                    ]);

                    $payment_details = PaymentLogs::findOrFail($old_tenant_log->id);
                    $this->payment_details = $payment_details;
                } // If Payment Pending
                elseif (!empty($old_tenant_log) && $old_tenant_log->payment_status == 'pending') {
                    PaymentLogs::findOrFail($old_tenant_log->id)->update([
                        'email' => $email,
                        'name' => $name,
                        'package_name' => $order_details->title,
                        'package_price' => $amount_to_charge,
                        'package_gateway' => $selected_payment_gateway,
                        'package_id' => $package_id,
                        'user_id' => auth()->guard('web')->user()->id ?? null,
                        'tenant_id' => $subdomain ?? null,
                        'status' => 'pending',
                        'payment_status' => 'pending',
                        'is_renew' => $old_tenant_log->renew_status != null ? 1 : 0,
                        'track' => Str::random(10) . Str::random(10),
                        'updated_at' => Carbon::now(),
                        'start_date' => $package_start_date,
                        'expire_date' => $package_expire_date
                    ]);

                    $payment_details = PaymentLogs::findOrFail($old_tenant_log->id);
                    $this->payment_details = $payment_details;
                }
            } // New Tenant + Plan (New Payment)
            else {
                $old_tenant_log = PaymentLogs::where(['user_id' => $auth_id, 'tenant_id' => trim($request->custom_subdomain)])->latest()->first();
                if (empty($old_tenant_log)) {
                    $payment_log_id = PaymentLogs::create([
                        'email' => $email,
                        'name' => $name,
                        'package_name' => $order_details->title,
                        'package_price' => $amount_to_charge,
                        'package_gateway' => $selected_payment_gateway,
                        'package_id' => $package_id,
                        'user_id' => auth()->guard('web')->user()->id ?? null,
                        'tenant_id' => $subdomain ?? null,
                        'status' => 'pending',
                        'payment_status' => 'pending',
                        'is_renew' => 0,
                        'track' => Str::random(10) . Str::random(10),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'start_date' => $package_start_date,
                        'expire_date' => $package_expire_date,
                    ])->id;

                    $payment_details = PaymentLogs::findOrFail($payment_log_id);
                    $this->payment_details = $payment_details;
                } else {
                    $old_tenant_log->update([
                        'email' => $email,
                        'name' => $name,
                        'package_name' => $order_details->title,
                        'package_price' => $amount_to_charge,
                        'package_gateway' => $selected_payment_gateway,
                        'package_id' => $package_id,
                        'user_id' => auth()->guard('web')->user()->id ?? null,
                        'status' => 'pending',
                        'payment_status' => 'pending',
                        'is_renew' => 0,
                        'track' => Str::random(10) . Str::random(10),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'start_date' => $package_start_date,
                        'expire_date' => $package_expire_date,
                    ]);

                    $payment_details = PaymentLogs::findOrFail($old_tenant_log->id);
                    $this->payment_details = $payment_details;
                }
            }

            DB::commit(); // Committing all the actions
        } catch (\Exception $exception) {
            DB::rollBack(); // Rollback all the actions
            return back()->with('msg', 'Something went wrong');
        }

        if ($request->selected_payment_gateway === 'manual_payment')
        {
            PaymentLogs::find($this->payment_details['id'])->update([
                'transaction_id' => $trasaction_id ?? '',
                'attachments' => $trasaction_attachment ?? '',
            ]);

            try {
                (new PaymentGateways())->send_order_mail($this->payment_details['id']);
            } catch (\Exception $e) {}

            return redirect()->route(self::SUCCESS_ROUTE, $this->payment_details['id']);
        } else {
            return $this->payment_with_gateway($request->selected_payment_gateway);
        }
    }

    /**
     * @param $payment_gateway_name
     * @return RedirectResponse
     */
    public function payment_with_gateway($payment_gateway_name) : RedirectResponse
    {
        try {
            $gateway_function = 'get_' . $payment_gateway_name . '_credential';
            $gateway = PaymentGatewayCredential::$gateway_function();

            return $gateway->charge_customer(
                $this->common_charge_customer_data($payment_gateway_name)
            );
        } catch (\Exception $e) {
            return back()->with(['msg' => $e->getMessage(), 'type' => 'danger']);
        }
    }

    public function common_charge_customer_data($payment_gateway_name): array
    {
        $user = Auth::guard('web')->user();
        $email = $user->email;
        $name = $user->name;

        return [
            'amount' => $this->total,
            'title' => $this->payment_details['package_name'],
            'description' => 'Payment For Package Order Id: #' . $this->payment_details['id'] . ' Package Name: ' . $this->payment_details['package_name']  . ' Payer Name: ' . $this->payment_details['name']  . ' Payer Email:' . $this->payment_details['email'] ,
            'ipn_url' => route('landlord.frontend.' . strtolower($payment_gateway_name) . '.ipn', $this->payment_details['id']),
            'order_id' => $this->payment_details['id'],
            'track' => \Str::random(36),
            'cancel_url' => route(self::CANCEL_ROUTE, $this->payment_details['id']),
            'success_url' => route(self::SUCCESS_ROUTE, $this->payment_details['id']),
            'email' => $email,
            'name' => $name,
            'payment_type' => 'order',
        ];
    }


    // IPNs
    public function paypal_ipn(): RedirectResponse
    {
        $paypal = PaymentGatewayCredential::get_paypal_credential();
        $payment_data = $paypal->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function paytm_ipn(): RedirectResponse
    {
        $paytm = PaymentGatewayCredential::get_paytm_credential();
        $payment_data = $paytm->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function flutterwave_ipn(): RedirectResponse
    {
        $flutterwave = PaymentGatewayCredential::get_flutterwave_credential();
        $payment_data = $flutterwave->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function stripe_ipn(): RedirectResponse
    {
        $stripe = PaymentGatewayCredential::get_stripe_credential();
        $payment_data = $stripe->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function razorpay_ipn(): RedirectResponse
    {
        $razorpay = PaymentGatewayCredential::get_razorpay_credential();
        $payment_data = $razorpay->ipn_response();

        return $this->common_ipn_data($payment_data);
    }

    public function paystack_ipn(): RedirectResponse
    {
        $paystack = PaymentGatewayCredential::get_paystack_credential();
        $payment_data = $paystack->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function payfast_ipn(): RedirectResponse
    {
        $payfast = PaymentGatewayCredential::get_payfast_credential();
        $payment_data = $payfast->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function mollie_ipn(): RedirectResponse
    {
        $mollie = PaymentGatewayCredential::get_mollie_credential();
        $payment_data = $mollie->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function midtrans_ipn(): RedirectResponse
    {
        $midtrans = PaymentGatewayCredential::get_midtrans_credential();
        $payment_data = $midtrans->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function cashfree_ipn(): RedirectResponse
    {
        $cashfree = PaymentGatewayCredential::get_cashfree_credential();
        $payment_data = $cashfree->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function instamojo_ipn(): RedirectResponse
    {
        $instamojo = PaymentGatewayCredential::get_instamojo_credential();
        $payment_data = $instamojo->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function marcadopago_ipn(): RedirectResponse
    {
        $marcadopago = PaymentGatewayCredential::get_marcadopago_credential();
        $payment_data = $marcadopago->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function squareup_ipn(): RedirectResponse
    {
        $squareup = PaymentGatewayCredential::get_squareup_credential();
        $payment_data = $squareup->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function cinetpay_ipn(): RedirectResponse
    {
        $cinetpay = PaymentGatewayCredential::get_cinetpay_credential();
        $payment_data = $cinetpay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function paytabs_ipn(): RedirectResponse
    {
        $paytabs = PaymentGatewayCredential::get_paytabs_credential();
        $payment_data = $paytabs->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function billplz_ipn(): RedirectResponse
    {
        $billplz = PaymentGatewayCredential::get_billplz_credential();
        $payment_data = $billplz->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function zitopay_ipn(): RedirectResponse
    {
        $zitopay = PaymentGatewayCredential::get_zitopay_credential();
        $payment_data = $zitopay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function toyyibpay_ipn(): RedirectResponse
    {
        $toyyibpay = PaymentGatewayCredential::get_toyyibpay_credential();
        $payment_data = $toyyibpay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function pagali_ipn(): RedirectResponse
    {
        $pagalipay = PaymentGatewayCredential::get_pagali_credential();
        $payment_data = $pagalipay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function authorizenet_ipn(): RedirectResponse
    {
        $authorizenet = PaymentGatewayCredential::get_authorizenet_credential();
        $payment_data = $authorizenet->ipn_response();

        return $this->common_ipn_data($payment_data);
    }

    public function siteways_ipn(): RedirectResponse
    {
        $authorizenet = PaymentGatewayCredential::get_siteways_credential();
        $payment_data = $authorizenet->ipn_response();

        return $this->common_ipn_data($payment_data);
    }

    public function transactionclud_api(): RedirectResponse
    {
        $transactionclud = PaymentGatewayCredential::get_transactionclud_credential();
        $payment_data = $transactionclud->ipn_response();

        return $this->common_ipn_data($payment_data);
    }

    public function wipay_ipn(): RedirectResponse
    {
        $wipay = PaymentGatewayCredential::get_wipay_credential();
        $payment_data = $wipay->ipn_response();

        return $this->common_ipn_data($payment_data);
    }

    public function kineticPay_ipn(): RedirectResponse
    {
        $kinetpay = PaymentGatewayCredential::get_kinetpay_credential();
        $payment_data = $kinetpay->ipn_response();

        return $this->common_ipn_data($payment_data);
    }

    public function senangpay_ipn(): RedirectResponse
    {
        $senangpay = PaymentGatewayCredential::get_senangpay_credential();
        $payment_data = $senangpay->ipn_response_recurring();

        return $this->common_ipn_data($payment_data);
    }

    public function saltpay_ipn(): RedirectResponse
    {
        $saltpay = PaymentGatewayCredential::get_saltpay_credential();
        $payment_data = $saltpay->ipn_response();

        return $this->common_ipn_data($payment_data);
    }

    public function iyzipay_ipn(): RedirectResponse
    {
        $iyzipay = PaymentGatewayCredential::get_iyzipay_credential();
        $payment_data = $iyzipay->ipn_response();

        return $this->common_ipn_data($payment_data);
    }

    public function order_payment_cancel_static (){
        
    }

    private function common_ipn_data($payment_data, $redirect = true): mixed
    {
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            // first update database and after updating database send mail for this order according to `
            $order_address = OrderAddress::where("order_id", $payment_data["order_id"])->first();
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            // now send mail to all user like vendor user and admin
            self::sendOrderMail($payment_data, $order_address?->toArray());
            // now add money to wallet account if vendor is
            WalletService::updateWallet($payment_data["order_id"]);

            if($redirect){
                return redirect()->route(self::SUCCESS_ROUTE, $payment_data['order_id']);
            }else{
                return [
                    "success" => true,
                    "type" => "success"
                ];
            }
        }

        if($redirect){
            return $this->cancel_page();
        }

        return ['success' => false, "type" => "danger"];
    }

    public function order_payment_success($id)
    {
        $orders = SubOrder::with(["order","vendor","orderItem","orderItem.product","orderItem.variant","orderItem.variant.productColor","orderItem.variant.productSize"])
            ->where("order_id", $id)->get();
        $payment_details = Order::with("address","paymentMeta","orderTrack")->find($id);

        return view('frontend.payment.payment-success', compact('orders','payment_details'));
    }

    private function update_database($order_id, $transaction_id)
    {
        Order::where('id', $order_id)->update([
            'transaction_id' => $transaction_id,
            'order_status' => 'complete',
            'payment_status' => 'complete',
            'updated_at' => Carbon::now()
        ]);
    }

    public static function __callStatic(string $name, array $arguments)
    {
        $instance = new static();

        if(!method_exists($instance, $name)){
            throw new RuntimeException("This method is not found.");
        }

        return call_user_func_array([$instance, $name],$arguments);
    }
}
