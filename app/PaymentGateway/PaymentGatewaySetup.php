<?php


namespace App\PaymentGateway;


use App\PaymentGateway\Gateways\FlutterWaveRave;
use App\PaymentGateway\Gateways\MolliePay;
use App\PaymentGateway\Gateways\Paypal;
use App\PaymentGateway\Gateways\PaystackPay;
use App\PaymentGateway\Gateways\Paytm;
use App\PaymentGateway\Gateways\Razorpay;
use App\PaymentGateway\Gateways\StripePay;

class PaymentGatewaySetup
{

    public static function gateway_settings()
    {
        return [
            'paypal' => [
                'client_id',
                'status',
                'logo'
            ],
            'manual_payment',
            'mollie',
            'paytm',
            'stripe',
            'razorpay',
            'flutterwave',
            'paystack'
        ];
    }

    public static function paypal()
    {
        return new Paypal();
    }

    public static function paytm()
    {
        return new Paytm();
    }

    public static function stripe()
    {
        return new StripePay();
    }

    public static function paystack()
    {
        return new PaystackPay();
    }

    public static function razorpay()
    {
        return new Razorpay();
    }

    public static function flutterwaverev()
    {
        return new FlutterWaveRave();
    }

    public static function mollie()
    {
        return new MolliePay();
    }

    public static function gateway_list()
    {
        return [
            'paypal',
            'mollie',
            'paytm',
            'stripe',
            'razorpay',
            'flutterwave',
            'paystack',
            'midtrans',
            'payfast',
            'cashfree',
            'instamojo',
            'marcadopago',
            'manual_payment',
            'bank_transfer',
            'cheque_payment',
        ];
    }

    public static function renderFrontendFormContent($cash_on_delivery = false)
    {
        $output = '<div class="payment-gateway-wrapper">';
        if (empty(get_static_option('site_payment_gateway'))) {
            return;
        }

        $all_gateway = PaymentGatewaySetup::gateway_list();

        $output .= '<input type="hidden" name="selected_payment_gateway" value="' . get_static_option('site_default_payment_gateway') . '">';
        $output .= '<ul>';

        if (!empty(get_static_option('cash_on_delivery_gateway'))) {
            $output .= '<li data-gateway="cash_on_delivery" ><div class="img-select">';
            $output .= render_image_markup_by_attachment_id(get_static_option('cash_on_delivery_preview_logo'));
            $output .= '</div></li>';
        }

        foreach ($all_gateway as $gateway) {
            if (!empty(get_static_option($gateway . '_gateway'))) :
                $class = (get_static_option('site_default_payment_gateway') == $gateway) ? 'class="selected"' : '';

                $output .= '<li data-gateway="' . $gateway . '" ' . $class . '><div class="img-select">';
                $output .= render_image_markup_by_attachment_id(get_static_option($gateway . '_preview_logo'));
                $output .= '</div></li>';
            endif;
        }

        $output .= '</ul>';
        $output .= '</div>';

        return $output;
    }
}
