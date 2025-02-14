<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomServiceByDc
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $messageBody, $toPhone)
    {
        $csmsId = rand(345, 4987) . "2934fe343";
        $domain = "https://smsplus.sslwireless.com";

        $params = [
            "api_token" => env('SSL_SMS_API_KEY'),
            "sid" => env('SSL_SMS_SID'),
            "msisdn" => $toPhone,
            "sms" => $messageBody,
            "csms_id" => $csmsId
        ];

        $url = trim($domain, '/') . "/api/v3/send-sms";
        $params = json_encode($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($params),
            'accept:application/json'
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        $request->attributes->add(['curlResponse' => $response]);


        return $next($request);
    }



}
