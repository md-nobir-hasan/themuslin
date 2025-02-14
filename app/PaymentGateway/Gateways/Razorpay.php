<?php


namespace App\PaymentGateway\Gateways;


use App\PaymentGateway\PaymentGatewayBase;
use Razorpay\Api\Api;

class Razorpay extends PaymentGatewayBase
{
    /**
     * this payment gateway will not work with this package
     * @ https://github.com/razorpay/razorpay-php
     * */
    public function charge_amount($amount)
    {
        //  Implement charge_amount() method.
        if (in_array(self::global_currency(), $this->supported_currency_list())){
            return $amount;
        }
        return self::get_amount_in_inr($amount);
    }

    /**
     *
     * @param array $args
     * require param list
     * request
     * @return array|string[]
     *
     */
    public function ipn_response(array $args)
    {
        //  Implement ipn_response() method.
        $request = $args['request'];
        //get API Configuration
        $api = new Api(get_static_option('razorpay_key'), get_static_option('razorpay_secret'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($request->razorpay_payment_id);

        if (!empty($request->razorpay_payment_id)) {
            try {
                $response = $api->payment->fetch($request->razorpay_payment_id)->capture(array('amount' => $payment['amount']));
                return $this->verified_data([
                   'status' => 'complete',
                   'transaction_id' =>  $payment->id
                ]);
            } catch (\Exception $e) {
                return ['status' => 'failed'];
            }
        }
        return ['status' => 'failed'];
    }

    /**
     *
     * @param array $args
     * @paral list
     * price
     * title
     * description
     * route
     * order_id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function charge_customer(array $args)
    {
        //  Implement charge_customer() method.
        $razorpay_data['currency'] =  $this->charge_currency();
        $razorpay_data['price'] = $this->charge_amount($args['price']);
        $razorpay_data['title'] = $args['title'];
        $razorpay_data['description'] = $args['description'];
        $razorpay_data['route'] = $args['route'];
        $razorpay_data['order_id'] = $args['order_id'];
        return view('payment.razorpay')->with('razorpay_data', $razorpay_data);
    }

    public function supported_currency_list()
    {
        //  Implement supported_currency_list() method.
        return ['INR'];
    }

    public function charge_currency()
    {
        //  Implement charge_currency() method.
        if (in_array(self::global_currency(), $this->supported_currency_list())){
            return self::global_currency();
        }
        return  "INR";
    }

    public function gateway_name()
    {
        //  Implement geteway_name() method.
        return 'razorpay';
    }
}