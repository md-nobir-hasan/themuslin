<?php


namespace App\PaymentGateway\Gateways;

use App\PaymentGateway\PaymentGatewayBase;
use Exception;
//use KingFlamez\Rave\Facades\Rave;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

class FlutterWaveRave extends PaymentGatewayBase
{
    /**
     * this payment gateway will not work without this package
     * @ https://github.com/kingflamez/laravelrave
     * */
    public function charge_amount($amount)
    {
        //  Implement charge_amount() method.
        if (in_array(self::global_currency(), $this->supported_currency_list())) {
            return $amount;
        }
        return self::get_amount_in_usd($amount);
    }

    /**
     *
     * @param array $args
     * @required param list
     * request
     *
     * @return array
     */
    public function ipn_response(array $args)
    {
        //  Implement ipn_response() method.
        $response = Flutterwave::verifyTransaction($args['request']->transaction_id);
        $status = $response['status'];

        if ($status === 'success') {
            $txRef = $response['data']['tx_ref'];
            $track = $response['data']['meta']['metavalue'];

            return $this->verified_data([
                'status' => 'complete',
                'transaction_id' => $txRef,
                'track' => $track,
            ]);
        }

        return ['status' => 'failed'];
    }

    /**
     *
     * @param array $args
     * name
     * email
     * ipn_route
     * amount
     * description
     * order_id
     * track
     *
     */
    public function charge_customer(array $args)
    {
        if ($this->charge_amount($args['amount']) > 1000) {
            return back()->with(['msg' => __('We could not process your request due to your amount is higher than the maximum.'), 'type' => 'danger']);
        }

        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => $this->charge_amount($args['amount']),
            'email' => $args['email'],
            'tx_ref' => $reference,
            'currency' => $this->charge_currency(),
            'redirect_url' => $args['callback'],
            'customer' => [
                'email' => $args['email'],
                "name" => $args['name']
            ],

            "customizations" => [
                "title" => get_static_option('site_' . get_user_lang() . '_title'),
                "description" => $args['description']
            ],
            'meta' =>  [
                'metaname' => 'track', 'metavalue' => $args['track'],
            ]
        ];

        $payment = Flutterwave::initializePayment($data);
        if ($payment['status'] === 'success') {
            return redirect($payment['data']['link']);
        } elseif ($payment['status'] === 'error') {
            return $payment['message'];
        }
        return redirect($payment['data']['link']);
    }

    public function supported_currency_list()
    {
        //  Implement supported_currency_list() method.
        return ['BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'MZN', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD'];
    }

    public function charge_currency()
    {
        //  Implement charge_currency() method.
        if (in_array(self::global_currency(), $this->supported_currency_list())) {
            return self::global_currency();
        }
        return "USD";
    }

    public function gateway_name()
    {
        //  Implement geteway_name() method.
        return 'flutterwaverave';
    }

    public function get_visitor_country()
    {
        $return_val = 'NG';
        $ip = getVisIpAddr();
        $ipdat = @json_decode(file_get_contents(
            "http://www.geoplugin.net/json.gp?ip=" . $ip
        ));

        $ipdat = (array)$ipdat;
        $return_val = isset($ipdat['geoplugin_countryCode']) ? $ipdat['geoplugin_countryCode'] : $return_val;

        return $return_val;
    }
}
