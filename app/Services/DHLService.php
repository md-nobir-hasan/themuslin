<?php

namespace App\Services;

use GuzzleHttp\Client;

class DHLService
{
    protected $client;
    protected $apiKey;
    protected $apiSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = '8RTS4QlHdqi0Ncr3GiT2IPnqcwklS0Oz';
        $this->apiSecret = '6WFPyGGh2hA7OTm2';
        $this->baseUrl = 'https://api-test.dhl.com/express/v1/'; // Test URL
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->apiSecret),
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    public function calculateShippingRate($data)
    {
        try {
            $response = $this->client->post('rates', [
                'json' => [
                    'plannedShippingDate' => date('Y-m-d'),
                    'unitOfMeasurement' => 'metric',
                    'isCustomsDeclarable' => false,
                    'accounts' => [
                        [
                            'typeCode' => 'shipper',
                            'number' => '123456789'
                        ]
                    ],
                    'customerDetails' => [
                        'shipperDetails' => [
                            'postalCode' => $data['shipper_postal_code'],
                            'cityName' => $data['shipper_city'],
                            'countryCode' => $data['shipper_country']
                        ],
                        'receiverDetails' => [
                            'postalCode' => $data['receiver_postal_code'],
                            'cityName' => $data['receiver_city'],
                            'countryCode' => $data['receiver_country']
                        ]
                    ],
                    'packages' => [
                        [
                            'weight' => $data['weight'],
                            'dimensions' => [
                                'length' => $data['length'],
                                'width' => $data['width'],
                                'height' => $data['height']
                            ]
                        ]
                    ]
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
} 