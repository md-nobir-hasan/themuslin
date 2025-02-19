<?php

namespace App\Http\Controllers;

use App\Services\DHLService;
use Illuminate\Http\Request;

use Modules\Product\Entities\Product as EntitiesProduct;
use Cart;
use Modules\CountryManage\Entities\Country;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Http;
class DHLShippingController extends Controller
{
    protected $dhlService;

    public function __construct(DHLService $dhlService)
    {
        $this->dhlService = $dhlService;
    }

    public function calculateShipping(Request $request)
    {
        $request->validate([
            'receiver_postal_code' => 'required',
            'receiver_city' => 'required',
            'receiver_country' => 'required',
            'weight' => 'required|numeric',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
        ]);

        $response = $this->dhlService->calculateShippingRate($request->all());

        return response()->json($response);
    }

    public function calu(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|exists:countries,id',
            'state' => 'required|exists:states,id',
        ]);
        $cart = Cart::instance('default')->content();

        $all_user_shipping = [];

        $all_country = Country::where('status', 'publish')
            ->where('id', 1)
            ->get()->toArray();
        $all_country = array_column($all_country, 'name', 'id');

        $all_cart_items = Cart::content();


        $prd_ids = $all_cart_items?->pluck('id')?->toArray();
         $products = Product::with('category', 'subCategory', 'childCategory')->whereIn('id', $prd_ids)->get();

        $packages = [];

        foreach ($products as $product) {
            $packages[] = [
                'weight' => (float)$product->weight,
                'dimensions' => [
                    'length' => (float)$product->length,
                    'width' => (float)$product->width,
                    'height' => (float)$product->height
                ]
            ];
        }

        // Now send the request
        $apiKey = config('services.dhl.key');
        $apiPassword = "D#1kE$1xV$6yQ!2i";
        $auth = base64_encode("$apiKey:$apiPassword");
        $url =  config('services.dhl.url');
        $messageReference = substr(md5(uniqid()), 0, 28);

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
            // 'x-version' => '1.0'   // Uncomment if needed
        ])->post($url, [
            'customerDetails' => [
                'shipperDetails' => [
                    'postalCode' => '1205',
                    'cityName' => 'Dhaka',
                    'countryCode' => 'BD',
                ],
                'receiverDetails' => [
                    'postalCode' => '10117',
                    'cityName' => 'Berlin',
                    'countryCode' => 'DE',
                ],
            ],
            'accounts' => [
                [
                    'typeCode' => 'shipper',
                    'number' => '525738901'
                ]
            ],
            'plannedShippingDateAndTime' => now()->addDays(2)->format('Y-m-d\TH:i:s \G\M\T+00:00'),
            'isCustomsDeclarable' => true,
            'unitOfMeasurement' => 'metric',
            'packages' => $packages // Dynamically created packages
        ]);

        try {
               $responseArray = $response->json();
        
            $cost0 =     $responseArray['products'][0]['totalPrice'][0]['price'];
            $cur0 =     $responseArray['products'][0]['totalPrice'][0]['priceCurrency'];
            
            $cost1 =     $responseArray['products'][1]['totalPrice'][0]['price'];
            $cur1 =     $responseArray['products'][1]['totalPrice'][0]['priceCurrency'];
            
            $cost2 =     $responseArray['products'][2]['totalPrice'][0]['price'];
            $cur2 =     $responseArray['products'][2]['totalPrice'][0]['priceCurrency'];
            return response()->json([
                'success' => true,
                'cost0' => $cost0.' '.$cur0,
                'cost1' => $cost1.' '.$cur1,
                'cost2' => $cost2.' '.$cur2,
                'message' => 'Shipping cost calculated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not calculate shipping cost: ' . $e->getMessage()
            ]);
        }    
        
     

         
         
    }
    
    
    public function calculate(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|exists:countries,id',
            'state' => 'required|exists:states,id',
        ]);

        try {
            $cost = $this->calculateBasedOnWeightAndLocation(
                $request->all()
            );

            return response()->json([
                'success' => true,
                'cost' => number_format($cost, 2) . ' ' . config('app.currency', 'BDT'),
                'message' => 'Shipping cost calculated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not calculate shipping cost: ' . $e->getMessage()
            ]);
        }
    }

    private function calculateBasedOnWeightAndLocation($data)
    {
        // Get cart items total weight
        $cartItems = Cart::instance('default')->content();
        $totalWeight = 0;
        
        foreach ($cartItems as $item) {
            $product = EntitiesProduct::find($item->id);
            $weight = $product->weight ?? 0.5; // Default weight if not set
            $totalWeight += ($weight * $item->qty);
        }

        // Base rates per country (you can move this to database or config)
        $baseRates = [
            '231' => 15, // USA
            '77' => 20,  // UK
            '39' => 18,  // Canada
            // Add more countries as needed
        ];

        // Get base rate for country, default to highest rate if country not found
        $baseRate = $baseRates[$data['country']] ?? max($baseRates);
        
        // Weight calculation (per kg)
        $weightRate = 2.5;
        $weightCost = $totalWeight * $weightRate;
        
        // Distance factor based on zone
        $distanceFactor = $this->getDistanceFactor($data['country']);
        
        // Calculate total shipping cost
        $totalCost = ($baseRate + $weightCost) * $distanceFactor;
        
        return $totalCost;
    }

    private function getDistanceFactor($countryId)
    {
        // Define zones and their factors
        $zones = [
            'zone1' => ['231', '77', '39'], // USA, UK, Canada
            'zone2' => ['other_ids'], // Other countries
        ];

        $factors = [
            'zone1' => 1.2,
            'zone2' => 1.5,
        ];

        foreach ($zones as $zone => $countries) {
            if (in_array($countryId, $countries)) {
                return $factors[$zone];
            }
        }

        return $factors['zone2']; // Default to highest factor
    }

    public function getCities(Request $request)
    {
        $request->validate([
            'country_id' => 'required'
        ]);

        $cities = \DB::table('cities')
            ->where('country_id', $request->country_id)
            ->select('id', 'name')
            ->get();

        return response()->json([
            'success' => true,
            'cities' => $cities
        ]);
    }
} 