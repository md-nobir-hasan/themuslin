<?php

namespace App\Http\Controllers;

use App\Services\DHLService;
use Illuminate\Http\Request;

use Modules\Product\Entities\Product as EntitiesProduct;
use Cart;

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