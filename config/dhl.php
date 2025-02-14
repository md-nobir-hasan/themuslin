<?php

return [
    'api_key' => env('DHL_API_KEY'),
    'api_secret' => env('DHL_API_SECRET'),
    'mode' => env('DHL_MODE', 'test'),
    'shipper_postal_code' => env('DHL_SHIPPER_POSTAL_CODE'),
    'shipper_city' => env('DHL_SHIPPER_CITY'),
    'shipper_country' => env('DHL_SHIPPER_COUNTRY'),
]; 