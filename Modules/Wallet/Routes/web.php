<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\Http\Controllers\User\WalletController;
use Modules\Wallet\Http\Controllers\UserWalletDepositController;
use Modules\Wallet\Http\Controllers\VendorWalletController;
use Modules\Wallet\Http\Controllers\VendorWalletGatewaySettingController;
use Modules\Wallet\Http\Controllers\WithdrawGatewayController;

// backend routes

Route::prefix("admin-home/wallet")->middleware(["auth:admin"])->as("admin.wallet.")->group(function (){
    Route::controller(WithdrawGatewayController::class)->prefix("withdraw")->as("withdraw.")->group(function (){
        Route::get("/gateway","gateway")->name("gateway")->permission("wallet-withdraw-gateway");
        Route::post("/gateway","storeGateway")->permission("wallet-withdraw-gateway");
        Route::put("gateway/update/{id?}", "updateGateway")->name("gateway.update")->permission("wallet-withdraw-gateway-update");
        Route::post("gateway/delete/{id}", "deleteGateway")->name("gateway.delete")->permission("wallet-withdraw-gateway-delete");
    });
});

Route::group(['prefix' => 'admin-home/wallet','as'=>'admin.wallet.', 'middleware' => ['adminglobalVariable','setlang:backend','auth:admin']], function () {
    Route::get( '/lists', 'Backend\WalletController@wallet_lists')->name('lists')->permission("wallet-lists");
    Route::get( '/vendor/lists', 'Backend\WalletController@vendor_wallet_list')->name('lists')->permission("wallet-vendor-lists");
    Route::get( '/customer/lists', 'Backend\WalletController@customer_wallet_list')->name('customer.lists')->permission("wallet-customer-lists");
    Route::get( '/delivery-man/lists', 'Backend\WalletController@delivery_man_wallet_list')->name('delivery-man.lists')->permission("wallet-delivery-man-lists");
    Route::post( '/status/{id}', 'Backend\WalletController@change_status')->name('status')->permission("wallet-status");
    Route::get( '/history/records', 'Backend\WalletController@wallet_history')->name('history')->permission("wallet-history-records");
    Route::post( '/history/records/status/{id}', 'Backend\WalletController@wallet_history_status')->name('history.status')->permission("wallet-history-records-status");
    Route::get( '/settings/update', 'Backend\WalletController@settings')->name('settings')->permission("wallet-settings-update");
    Route::post( '/settings/update', 'Backend\WalletController@settings_update')->permission("wallet-settings-update");
    Route::get("withdraw-request", "Backend\WalletController@withdrawRequestPage")->name("withdraw-request")->permission("wallet-withdraw-request");
    Route::post("withdraw-request/update", "Backend\WalletController@updateWithdrawRequest")->name("withdraw-request.update")->permission("wallet-withdraw-request-update");
    Route::get("delivery-man-withdraw-request", "Backend\WalletController@deliveryManWithdrawRequest")->name("delivery-man-withdraw-request")->permission("wallet-delivery-man-withdraw-request");
    Route::post("delivery-man-withdraw-request/update", "Backend\WalletController@updateDeliveryManWithdrawRequest")->name("delivery-man-withdraw-request.update")->permission("wallet-delivery-man-withdraw-request-update");
    Route::get('details/{id}','Backend\WalletController@history_details')->name('history.details')->permission("wallet-details");
    Route::get('search-history', 'Backend\WalletController@search_history')->name('wallet.search')->permission("wallet-search-history");
});

Route::prefix("vendor-home/wallet")->middleware(["auth:vendor",'userEmailVerify','setlang:backend','adminglobalVariable'])->as("vendor.wallet.")->group(function (){
    Route::controller(VendorWalletController::class)->group(function () {
        Route::get("/", "index")->name("home");
        Route::get("withdraw", "withdraw")->name("withdraw");
        Route::post("withdraw", "handleWithdraw");
        Route::get("withdraw-request", "withdrawRequestPage")->name('withdraw-request');
        Route::get("history", "walletHistory")->name('history');
    });

    Route::prefix("/gateway")->name("withdraw.gateway.")->controller(VendorWalletGatewaySettingController::class)->group(function (){
        Route::get("/","index")->name("index");
        Route::put("/update","update")->name("update");
    });
});

Route::prefix("user-home/wallet")->middleware(["auth:web","globalVariable"])->as('user-home.wallet.')->group(function () {
    Route::controller(WalletController::class)->group(function () {
        Route::get('history','wallet_history')->name('history');
        Route::get('paginate/data', 'pagination')->name('paginate.data');
        Route::get('search-history', 'search_history')->name('search');
        Route::post('deposit', 'deposit')->name('deposit');
        Route::get('deposit-cancel-static','deposit_payment_cancel_static')->name('deposit.payment.cancel.static');
    });

    Route::controller(UserWalletDepositController::class)->group(function () {
        Route::get('paypal-ipn', 'paypal_ipn_for_wallet')->name('paypal.ipn.wallet');
        Route::post('paytm-ipn', 'paytm_ipn_for_wallet')->name('paytm.ipn.wallet');
        Route::get('paystack-ipn', 'paystack_ipn_for_wallet')->name('paystack.ipn.wallet');
        Route::get('mollie/ipn', 'mollie_ipn_for_wallet')->name('mollie.ipn.wallet');
        Route::get('stripe/ipn', 'stripe_ipn_for_wallet')->name('stripe.ipn.wallet');
        Route::post('razorpay-ipn', 'razorpay_ipn_for_wallet')->name('razorpay.ipn.wallet');
        Route::get('flutterwave/ipn', 'flutterwave_ipn_for_wallet')->name('flutterwave.ipn.wallet');
        Route::get('midtrans-ipn', 'midtrans_ipn_for_wallet')->name('midtrans.ipn.wallet');
        Route::get('payfast-ipn', 'payfast_ipn_for_wallet')->name('payfast.ipn.wallet');
        Route::post('cashfree-ipn', 'cashfree_ipn_for_wallet')->name('cashfree.ipn.wallet');
        Route::get('instamojo-ipn', 'instamojo_ipn_for_wallet')->name('instamojo.ipn.wallet');
        Route::get('marcadopago-ipn', 'marcadopago_ipn_for_wallet')->name('marcadopago.ipn.wallet');
        Route::get('squareup-ipn', 'squareup_ipn_for_wallet')->name('squareup.ipn.wallet');
        Route::post('cinetpay-ipn', 'cinetpay_ipn_for_wallet')->name('cinetpay.ipn.wallet');
        Route::post('paytabs-ipn', 'paytabs_ipn_for_wallet')->name('paytabs.ipn.wallet');
        Route::post('billplz-ipn', 'billplz_ipn_for_wallet')->name('billplz.ipn.wallet');
        Route::post('zitopay-ipn', 'zitopay_ipn_for_wallet')->name('zitopay.ipn.wallet');
        Route::post('toyyibpay-ipn', 'toyyibpay_ipn_for_wallet')->name('toyyibpay.ipn.wallet');
        Route::get('authorize-ipn', 'authorizenet_ipn_for_wallet')->name('authorize.ipn.wallet');
        Route::post('pagali-ipn', 'pagali_ipn_for_wallet')->name('pagali.ipn.wallet');
        Route::post('siteways-ipn', 'siteways_ipn_for_wallet')->name('siteways.ipn.wallet');
        Route::get('transactioncloud-ipn', 'transactioncould_ipn_for_wallet')->name('transactioncloud.ipn');
        Route::get('wipay-ipn', 'wipay_ipn')->name('wipay.ipn');
        Route::post('kineticpay-ipn', 'kineticPay_ipn')->name('kineticPay.ipn');
        Route::get('senangpay-ipn', 'senangpay_ipn')->name('senangpay.ipn');
        Route::post('salt-ipn', 'salt_ipn')->name('saltpay.ipn');
        Route::post('iyzipay-ipn', 'iyzipay_ipn')->name('iyzipay.ipn');
    });
});

Route::group(['prefix' => 'wallet'], function () {
    //wallet payment routes
    Route::get('/paypal-ipn', 'Frontend\BuyerWalletPaymentController@paypal_ipn_for_wallet')->name('buyer.paypal.ipn.wallet');
    Route::post('/paytm-ipn', 'Frontend\BuyerWalletPaymentController@paytm_ipn_for_wallet')->name('buyer.paytm.ipn.wallet');
    Route::get('/paystack-ipn', 'Frontend\BuyerWalletPaymentController@paystack_ipn_for_wallet')->name('buyer.paystack.ipn.wallet');
    Route::get('/mollie/ipn', 'Frontend\BuyerWalletPaymentController@mollie_ipn_for_wallet')->name('buyer.mollie.ipn.wallet');
    Route::get('/stripe/ipn', 'Frontend\BuyerWalletPaymentController@stripe_ipn_for_wallet')->name('buyer.stripe.ipn.wallet');
    Route::post('/razorpay-ipn', 'Frontend\BuyerWalletPaymentController@razorpay_ipn_for_wallet')->name('buyer.razorpay.ipn.wallet');
    Route::get('/flutterwave/ipn', 'Frontend\BuyerWalletPaymentController@flutterwave_ipn_for_wallet')->name('buyer.flutterwave.ipn.wallet');
    Route::get('/midtrans-ipn', 'Frontend\BuyerWalletPaymentController@midtrans_ipn_for_wallet')->name('buyer.midtrans.ipn.wallet');
    Route::post('/payfast-ipn', 'Frontend\BuyerWalletPaymentController@payfast_ipn_for_wallet')->name('buyer.payfast.ipn.wallet');
    Route::post('/cashfree-ipn', 'Frontend\BuyerWalletPaymentController@cashfree_ipn_for_wallet')->name('buyer.cashfree.ipn.wallet');
    Route::get('/instamojo-ipn', 'Frontend\BuyerWalletPaymentController@instamojo_ipn_for_wallet')->name('buyer.instamojo.ipn.wallet');
    Route::get('/marcadopago-ipn', 'Frontend\BuyerWalletPaymentController@marcadopago_ipn_for_wallet')->name('buyer.marcadopago.ipn.wallet');
    Route::get('/squareup-ipn', 'Frontend\BuyerWalletPaymentController@squareup_ipn_for_wallet')->name('buyer.squareup.ipn.wallet');
    Route::post('/cinetpay-ipn', 'Frontend\BuyerWalletPaymentController@cinetpay_ipn_for_wallet')->name('buyer.cinetpay.ipn.wallet');
    Route::post('/paytabs-ipn', 'Frontend\BuyerWalletPaymentController@paytabs_ipn_for_wallet')->name('buyer.paytabs.ipn.wallet');
    Route::post('/billplz-ipn', 'Frontend\BuyerWalletPaymentController@billplz_ipn_for_wallet')->name('buyer.billplz.ipn.wallet');
    Route::post('/zitopay-ipn', 'Frontend\BuyerWalletPaymentController@zitopay_ipn_for_wallet')->name('buyer.zitopay.ipn.wallet');
    Route::post('/toyyibpay-ipn', 'Frontend\BuyerWalletPaymentController@toyyibpay_ipn_for_wallet')->name('buyer.toyyibpay.ipn.wallet');
    Route::post('/siteways-ipn', 'Frontend\BuyerWalletPaymentController@siteways_ipn')->name('siteways.ipn');
    Route::get('/transactioncloud-ipn', 'Frontend\BuyerWalletPaymentController@transactioncould')->name('payment.transactioncloud.ipn');
});