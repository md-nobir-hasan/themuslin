<?php


namespace App\PaymentGateway\Gateways;


use App\PaymentGateway\PaymentGatewayBase;
use Illuminate\Support\Str;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class Paypal extends PaymentGatewayBase
{
    private $_api_context;
    /**
     * this class need this package to work @https://github.com/paypal/PayPal-PHP-SDK
     * @since 1.0.0
     * */
    public function __construct(){
        /** PayPal api context **/
        $paypal_conf = config('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    /**
     * charge_amount();
     * @required param list
     * $amount
     *
     *
     * */
    public function charge_amount($amount)
    {
        //  Implement charge_amount() method.
        if (in_array(self::global_currency(), $this->supported_currency_list())){
            return $amount;
        }
        return self::get_amount_in_usd($amount);
    }
    /**
     * @required param list
     * $args['amount']
     * $args['description']
     * $args['item_name']
     * $args['ipn_url']
     * $args['cancel_url']
     * $args['payment_track']
     * return redirect url for paypal
     * */

    public function charge_customer($args)
    {
        //  Implement ipn_response() method.
        /* new code */
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $currency = $this->charge_currency();
        $charge_amount = (int) $this->charge_amount($args['amount']);

        $item_1 = new Item();
        $item_1->setName($args['item_name'])/** item name **/
        ->setCurrency($currency)
            ->setQuantity(1)
            ->setPrice($charge_amount);
        /** unit price **/

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency($currency)
            ->setTotal($charge_amount);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($args['description']);

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl($args['ipn_url'])/** Specify return URL **/
        ->setCancelUrl($args['cancel_url']);

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));


        try {

            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (config('app.debug')) {
                return redirect()->to($args['cancel_url']); //connection timeout
            }

            return redirect()->to($args['cancel_url']); //some error occur, then redirect to cancel page

        }


        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref(); //set redirect url for payment
                break;
            }
        }

        /** add payment ID to session, this need to verify paypal transaction from paypal ipn function **/
        session()->put('paypal_payment_id', $payment->getId());
        session()->put('paypal_track', $args['payment_track']);

        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return $redirect_url;
        }
        abort('404'); //not redirect to paypal, that's why redirect in 404 page
    }




    /**
     * @required param list
     * $args['request']
     * $args['cancel_url']
     * $args['success_url']
     *
     * return @void
     * */
    public function ipn_response($args){

        /** Get the payment ID before session clear **/
        $payment_id = session()->get('paypal_payment_id');
        $paypal_track = session()->get('paypal_track');
        $request = $args['request'];

        /** clear the session payment ID **/
        session()->forget(['paypal_payment_id','paypal_track']);

        if (empty($request->PayerID) || empty($request->token)) {
            return redirect()->to($args['cancel_url']);
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->PayerID);

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() === 'approved') {
            return $this->verified_data(['transaction_id' => $payment_id]);
        }
        return redirect()->to($args['cancel_url']);
    }

    /**
     * geteway_name();
     * return @string
     * */
    public function gateway_name(){
        //  Implement geteway_name() method.
        return 'paypal';
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
        return  "USD";
    }
    /**
     * supported_currency_list();
     * it will returl all of supported currency for the payment gateway
     * return array
     * */
    public function supported_currency_list(){
        return ['AUD', 'BRL', 'CAD', 'CNY', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'INR', 'ILS', 'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK', 'PHP', 'PLN', 'GBP', 'RUB', 'SGD', 'SEK', 'CHF', 'THB', 'USD'];
    }

}