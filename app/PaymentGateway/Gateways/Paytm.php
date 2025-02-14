<?php


namespace App\PaymentGateway\Gateways;


use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\PaymentGateway\PaymentGatewayBase;
use Illuminate\Support\Str;

class Paytm extends PaymentGatewayBase
{
    /**
     * this class need this package to work  https://github.com/anandsiddharth/laravel-paytm-wallet
     * @since 1.0.0
     * */

    /**
     * charge_amount();
     * @required param list
     * int|float amount
     *
     *
     * @param $amount
     * @return mixed|string
     */
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
     * ipn_response()
     * @required params
     * srting/url cancel_url
     * @param array $args
     * @return array|string[]
     */
    public function ipn_response(array $args = [])
    {
        //  Implement ipn_response() method.
        $transaction = PaytmWallet::with('receive');
        $response = $transaction->response(); // To get raw response as array
        //Check out response parameters sent by paytm here -> http://paywithpaytm.com/developer/paytm_api_doc?target=interpreting-response-sent-by-paytm
        if ($transaction->isSuccessful()) {

            return $this->verified_data([
                'transaction_id' => $response['TXNID']
            ]);

        }
        return ['status' => 'failed'];
    }

    /**
     *
     * charge_customer()
     * @required params
     * int order_id
     * string name
     * string email
     * int/float amount
     * string/url callback_url
     * */
    public function charge_customer(array $args)
    {
        //  Implement charge_customer() method.
        $charge_amount = $this->charge_amount($args['amount']);

        $payment = PaytmWallet::with('receive');
        $payment->prepare([
            'order' => $args['order_id'],
            'user' => Str::slug($args['name']),
            'mobile_number' => random_int(9999, 99999999),
            'email' => $args['email'],
            'amount' => number_format((float) $charge_amount, 2, '.', ''),
            'callback_url' => $args['callback_url']
        ]);
        return $payment->receive();
    }
    /**
     * supported_currency_list();
     * it will returl all of supported currency for the payment gateway
     * return array
     * */
    public function supported_currency_list()
    {
        //  Implement supported_currency_list() method.
        return ['INR'];
    }
    /**
     * charge_currency();
     * return @string
     * */
    public function charge_currency()
    {
        //  Implement charge_currency() method.
        if (in_array(self::global_currency(), $this->supported_currency_list())){
            return self::global_currency();
        }
        return  "INR";
    }
    /**
     * geteway_name();
     * return @string
     * */
    public function gateway_name()
    {
        //  Implement geteway_name() method.
        return 'paytm';
    }
}