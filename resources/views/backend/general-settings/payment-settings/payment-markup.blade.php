@php

    $payment_gateway_markup = [
       'paypal'=> [
            [
                'title'=> 'Paypal Settings',
                'info'=> 'if your currency is not available in paypal, it will convert you currency value to USD value based on your currency exchange rate.',
                'name'=> 'paypal',
                'logo'=> 'paypal_logo',
                'fields'=> ['paypal_mode','paypal_sandbox_client_id','paypal_sandbox_client_secret','paypal_sandbox_app_id','paypal_live_client_id','paypal_live_client_secret','paypal_live_app_id']
            ]
        ],

        'paytm'=> [
            [
                'title'=> 'Paytm Settings',
                'info'=> 'if your currency is not available in paytm, it will convert you currency value to INR value based on your currency exchange rate.',
                'name'=> 'paytm',
                'logo'=> 'paytm_logo',
                'fields'=> ['paytm_merchant_key','paytm_merchant_mid','paytm_merchant_website','paytm_channel','paytm_industry_type']
            ]
        ],

        'stripe'=> [
            [
                'title'=> 'Stripe Settings',
                'name'=> 'stripe',
                'logo'=> 'stripe_logo',
                'fields'=> ['stripe_public_key','stripe_secret_key']
            ]
        ],

        'razorpay'=> [
            [
                'title'=> 'Razorpay Settings',
                'info' => 'if your currency is not available in Razorpay, it will convert you currency value to INR value based on your currency exchange rate.',
                'name'=> 'razorpay',
                'logo'=> 'razorpay_logo',
                'fields'=> ['razorpay_api_key','razorpay_api_secret']
            ]
        ],

        'paystack'=> [
            [
                'title'=> 'Paystack Settings',
                'info' => 'if your currency is not available in Paystack, it will convert you currency value to NGN value based on your currency exchange rate.',
                'name'=> 'paystack',
                'logo'=> 'paystack_logo',
                'fields'=> ['paystack_public_key','paystack_secret_key','paystack_merchant_email']
            ]
        ],

       'mollie'=> [
            [
                'title'=> 'Mollie Settings',
                'info' => 'if your currency is not available in mollie, it will convert you currency value to USD value based on your currency exchange rate.',
                'name'=> 'mollie',
                'logo'=> 'mollie_logo',
                'fields'=> ['mollie_public_key']
            ]
        ],

        'flutterwave'=> [
            [
                'title'=> 'Flutterwave Settings',
                'info' => 'if your currency is not available in flutterwave, it will convert you currency value to USD value based on your currency exchange rate.',
                'name'=> 'flutterwave',
                'logo'=> 'flutterwave_logo',
                'fields'=> ['flw_public_key','flw_secret_key','flw_secret_hash']
            ]
        ],

        'midtrans'=> [
            [
                'title'=> 'Midtrans Settings',
                'name'=> 'midtrans',
                'logo'=> 'midtrans_logo',
                'fields'=> ['midtrans_merchant_id','midtrans_server_key','midtrans_client_key']
            ]
        ],

       'payfast'=> [
            [
                'title'=> 'Payfast Settings',
                'name'=> 'payfast',
                'logo'=> 'payfast_logo',
                'fields'=> ['payfast_merchant_id','payfast_merchant_key','payfast_passphrase','payfast_itn_url']
            ]
        ],

       'cashfree'=> [
            [
                'title'=> 'Cashfree Settings',
                'name'=> 'cashfree',
                'logo'=> 'cashfree_logo',
                'fields'=> ['cashfree_app_id','cashfree_secret_key']
            ]
        ],

       'instamojo'=> [
            [
                'title'=> 'Instamojo Settings',
                'name'=> 'instamojo',
                'logo'=> 'instamojo_logo',
                'fields'=> ['instamojo_client_id','instamojo_client_secret','instamojo_username','instamojo_password']
            ]
        ],

           'marcadopago'=> [
            [
                'title'=> 'Marcedopago Settings',
                'name'=> 'marcadopago',
                'logo'=> 'marcadopago_logo',
                'fields'=> ['marcado_pago_client_id','marcado_pago_client_secret']
            ]
        ],

         'manual_payment'=> [
            [
                'title'=> 'Manual Payment Settings',
                'name'=> 'manual_payment',
                'logo'=> 'manual_payment_logo',
                'fields'=> ['site_manual_payment_name','site_manual_payment_description']
            ]
        ],
    ];

@endphp
