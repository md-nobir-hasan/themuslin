<?php

namespace Modules\Order\Services;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\Order\Abstract\OrderAbstract;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\SubOrder;
use Modules\Order\Entities\SubOrderItem;
use Modules\Order\Traits\OrderTrait;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Http;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
class DhlshipmentService extends OrderAbstract
{

    use OrderTrait;

    public function __construct()
    {
        $this->couponAmount = 0;
    }


    public static function cratedhlshipment($orderId)
    {
//            $orderId = 37; for test
            $orderData = [];
            $payment_details = Order::with('address', 'paymentMeta')->find($orderId);
            $prd_ids = SubOrderItem::where('order_id',$orderId)->pluck('product_id')?->toArray();
            $products = Product::with('category', 'subCategory', 'childCategory')->whereIn('id', $prd_ids)->get();        
                    
            if($payment_details){
                $countryData = Country::where('id', $payment_details->address->country_id)->first();
                $StateData = State::where('country_id', $payment_details->address->country_id)
                    ->where('id', $payment_details->address->state_id)
                    ->first();
              
                $name= $payment_details->address->name;
                $email= $payment_details->address->email;
                $phone= $payment_details->address->phone;
                $country= $countryData->name;
                $code= $countryData->code;
                $state= $StateData->name;
                $address= $payment_details->address->address;
                $zipcode= $payment_details->address->zipcode;
                $packages = [];

                foreach ($products as $product) {
                    $packages[] = [
                        'typeCode' => '2BP',
                        'weight' => (float)$product->weight,
                        'dimensions' => [
                            'length' => (float)$product->length,
                            'width' => (float)$product->width,
                            'height' => (float)$product->height
                        ]
                    ];
                }
               $apiKey = config('services.dhl.key');
                $apiPassword = config('services.dhl.password');
                $urlshipment = config('services.dhl.urlshipment');
       
                $auth = base64_encode("$apiKey:$apiPassword");

                $messageReference = substr(md5(uniqid()), 0, 28);
                $shipmentDate = now()->addDays(1)->format('Y-m-d\TH:i:s \G\M\T+00:00'); 

            $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $auth,
            'Content-Type' => 'application/json',
            'Message-Reference' => $messageReference,
            'Message-Reference-Date' => now()->toIso8601String(),
            'Plugin-Name' => 'TestPlugin',
            'Plugin-Version' => '1.0.0',
            'Shipping-System-Platform-Name' => 'TestPlatform',
            'Shipping-System-Platform-Version' => '2.0.0',
            'Webstore-Platform-Name' => 'LaravelStore',
            'Webstore-Platform-Version' => '1.5.2',
        ])->post($urlshipment, [
            'plannedShippingDateAndTime' => $shipmentDate,
            'pickup' => [
                'isRequested' => false,
                'closeTime' => '18:00',
                'location' => 'reception',
                'specialInstructions' => [
                    [
                        'value' => 'please ring door bell',
                        'typeCode' => 'TBD',
                    ],
                ],
            ],
            'customerDetails' => [
                'shipperDetails' => [
                    'postalAddress' => [
                        "addressLine1" => "123 Main St",
                        'postalCode' => '1205',
                        'cityName' => 'Dhaka',
                        'countryCode' => 'BD',
                    ],
                    'contactInformation' => [
                        'email' => 'shipper@example.com',
                        'phone' => '+49123456789',
                        'companyName' => 'Shipper Company',
                        'fullName' => 'John Doe',
                    ],
                ],
                'receiverDetails' => [
                    'postalAddress' => [
                        'postalCode' => $zipcode,
                        'cityName' => $state,
                        'countryCode' => $code,
                        'addressLine1' => '456 Another Street',
                    ],
                    'contactInformation' => [
                        'email' => $email,
                        'phone' => $phone,
                        'companyName' => 'Receiver Company',
                        'fullName' => $name,
                    ],
                ],
            ],
            'accounts' => [
                [
                    'typeCode' => 'shipper',
                    'number' => '525738901',  // Use valid test account
                ],
            ],
            'productCode' => 'P',
            'localProductCode' => 'P',
            'content' => [
                'packages' => $packages,
                'isCustomsDeclarable' => true,
                'declaredValue' => 150,
                'declaredValueCurrency' => 'EUR',
                'incoterm' => 'DAP',
                'description' => 'DAP',
                'unitOfMeasurement' => 'metric',
                    'exportDeclaration' => [
                     'invoice' => [
                        'number' => 'INV-123456',
                        'date' => now()->format('Y-m-d'), 
                    ],   
                    'exportReasonType' => 'permanent',
                    'placeOfIncoterm' => 'Dhaka',
                    'exporter' => [
                        'id' => '123456',
                        'code' => 'EX1234',
                    ],
                    'lineItems' => [
                        [
                            'number' => 1,
                            'description' => 'Electronics',
                            'price' => 150,
                            'quantity' => ['value' => 1, 'unitOfMeasurement' => 'PCS'],
                            'weight' => ['netValue' => 5.0, 'grossValue' => 5.5],
                            'manufacturerCountry' => 'BD',
                        ],
                    ],
                ],
            ],

            'valueAddedServices' => [
                [
                    'serviceCode' => 'II',
                    'value' => 100,
                    'currency' => 'GBP',
                    'method' => 'cash',
                    'dangerousGoods' => [
                        [
                            'contentId' => '908',
                            'dryIceTotalNetWeight' => 12,
                            'customDescription' => '1 package Lithium ion batteries in compliance with Section II of P.I. 9661',
                            'unCodes' => ["1234"],
                        ],
                    ],
                ],
            ],
        ]);
            
        $responseArray = $response->json();
        dd($responseArray);
         try {
                 Order::where("id",$orderId)->update([
                    "shipmenttrackingnumber" => $responseArray['shipmentTrackingNumber'],
                ]);

                return true;
              } catch (\Exception $e) {
                return false;
            }       
        }     
    }

}