<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShippingAddressRequest;
use App\Http\Services\ShippingAddressServices;
use App\Shipping\ShippingAddress;
use Illuminate\Http\Request;

class FrontendShippingAddresssController extends Controller
{
    public function store(StoreShippingAddressRequest $request){
        $data = $request->validated();

        return ShippingAddressServices::store($data);
    }
}
