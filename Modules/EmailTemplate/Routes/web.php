<?php

use Illuminate\Support\Facades\Route;
use Modules\EmailTemplate\Http\Controllers\DeliveryManEmailController;
use Modules\EmailTemplate\Http\Controllers\EmailTemplateController;

Route::controller(EmailTemplateController::class)->prefix("admin-home/email-template")->as("admin.email-template.")->middleware(["auth:admin"])->group(function(){
    Route::prefix("refund")->as("refund.")->group(function (){
        Route::get("request-send", "requestSend")->name('request-send')->permission("email-template-request-send");
        Route::put("request-send", "updateRefundRequestSend")->permission("email-template-request-send");

        Route::get("request-approve", "refundRequestApproved")->name('request-approved')->permission("email-template-request-approve");
        Route::put("request-approve", "updateRefundRequestApproved")->permission("email-template-request-approve");

        Route::get("request-declined", "refundRequestDeclined")->name('request-declined')->permission("email-template-request-declined");
        Route::put("request-declined", "updateRefundRequestDeclined")->permission("email-template-request-declined");

        Route::get("request-cancel", "refundRequestCancel")->name('request-cancel')->permission("email-template-request-cancel");
        Route::put("request-cancel", "updateRefundRequestCancel")->permission("email-template-request-cancel");

        Route::get("request-ready-for-pickup", "refundRequestReadyForPickup")->name('request-ready-for-pickup')->permission("email-template-request-ready-for-pickup");
        Route::put("request-ready-for-pickup", "updateRefundRequestReadyForPickup")->permission("email-template-request-ready-for-pickup");

        Route::get("request-picked-up", "refundRequestPickedUp")->name('request-picked-up')->permission("email-template-request-picked-up");
        Route::put("request-picked-up", "updateRefundRequestPickedUp")->permission("email-template-request-picked-up");

        Route::get("request-on-the-way", "refundRequestOnTheWay")->name('request-on-the-way')->permission("email-template-request-on-the-way");
        Route::put("request-on-the-way", "updateRefundRequestOnTheWay")->permission("email-template-request-on-the-way");

        Route::get("request-returned", "refundRequestReturned")->name('request-returned')->permission("email-template-request-returned");
        Route::put("request-returned", "updateRefundRequestReturned")->permission("email-template-request-returned");

        Route::get("request-verify-product", "refundRequestVerifyProduct")->name('refund-request-verify-product')->permission("email-template-request-verify-product");
        Route::put("request-verify-product", "updateRefundRequestVerifyProduct")->permission("email-template-request-verify-product");

        Route::get("request-payment-processing", "refundRequestPaymentProcessing")->name('refund-request-payment-processing')->permission("email-template-request-payment-processing");
        Route::put("request-payment-processing", "updateRefundRequestPaymentProcessing")->permission("email-template-request-payment-processing");

        Route::get("request-payment-transferred", "refundRequestPaymentTransferred")->name('refund-request-payment-transferred')->permission("email-template-request-payment-transferred");
        Route::put("request-payment-transferred", "updateRefundRequestPaymentTransferred")->permission("email-template-request-payment-transferred");

        Route::get("request-payment-completed", "refundRequestPaymentCompleted")->name('refund-request-payment-completed')->permission("email-template-request-payment-completed");
        Route::put("request-payment-completed", "updateRefundRequestPaymentCompleted")->permission("email-template-request-payment-completed");
    });

    Route::prefix("delivery-man")->controller(DeliveryManEmailController::class)->as("delivery-man.")->group(function (){
        Route::get("assign-mail", "assignMail")->name('assign-mail')->permission('email-template-assign-mail');
        Route::put("assign-mail", "handleAssignMail")->permission('email-template-assign-mail');

        // assign mail to user
        Route::get("assign-mail-to-user", "assignMailToUser")->name('assign-mail-to-user')->permission('email-template-assign-mail-to-user');
        Route::put("assign-mail-to-user", "handleAssignMailToUser")->permission('email-template-assign-mail-to-user');
    });

    Route::get('all-templates','all_templates')->name('email.template.all')->permission('email-template-all-templates');
});

