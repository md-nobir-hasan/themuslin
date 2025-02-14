<?php

namespace App\Http\Controllers;

use App\Http\Requests\Frontend\SubmitCheckoutRequest;
use App\Http\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(SubmitCheckoutRequest $request){
        dd(CheckoutService::storeBillingAddress($request->validated()),$request->validated());
    }
}
