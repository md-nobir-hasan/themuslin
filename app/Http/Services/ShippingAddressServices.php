<?php

namespace App\Http\Services;

use App\Shipping\ShippingAddress;
use Illuminate\Http\JsonResponse;

class ShippingAddressServices
{
    public static function store($data, $isApi = false): JsonResponse
    {
        $query = ShippingAddress::create($data);

        if(!$isApi){
            $loadView = view("frontend.cart.partials.shipping-address-option",with(["shipping_address" => $query]))->render();
        }

        return response()->json([
            'success' => (bool) $query ?? false,
            "msg" => !empty($query) ? "Successfully created new shipping address" : "Failed to create new shipping address" ,
            'data' => $query,
            "option" => !$isApi ? $loadView : null
        ]);
    }
}