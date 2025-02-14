<?php

namespace App\Http\Controllers;

use App\InternationalShipping;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InternationalShippingController extends Controller
{
    public function updateSettings(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'shipping_method' => 'required|string',
            'api_key' => 'required|string',
            'api_secret' => 'required|string',
            'api_url' => 'nullable|url',
            'api_url_test' => 'nullable|url',
            'is_test_mode' => 'nullable'
        ]);

        $shipping = InternationalShipping::updateOrCreate(
            ['slug' => Str::slug($request->shipping_method)],
            [
                'name' => $request->shipping_method,
                'api_key' => $request->api_key,
                'api_secret' => $request->api_secret,
                'api_url' => $request->api_url,
                'api_url_test' => $request->api_url_test,
                'is_active' => true
            ]
        );

        return response()->json([
            'success' => true,
            'message' => __('Shipping method updated successfully')
        ]);
    }

    public function toggleStatus(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'shipping_method' => 'required|string',
            'status' => 'required'
        ]);

        $shipping = InternationalShipping::where('slug', Str::slug($request->shipping_method))
            ->update(['is_active' => $request->status ? true : false]);

        return response()->json([
            'success' => true,
            'message' => __('Status updated successfully')
        ]);
    }

    public function getSettings($method)
    {
        $shipping = InternationalShipping::where('slug', $method)->first();
        
        return response()->json([
            'success' => true,
            'data' => $shipping
        ]);
    }
} 