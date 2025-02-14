<?php

namespace Modules\Order\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\ShippingModule\Entities\AdminShippingMethod;
use Modules\ShippingModule\Entities\VendorShippingMethod;

class OrderShippingChargeService
{
    public static function getShippingCharge($shippingCost): array
    {
        // first get admin shippingCost id and store it in a temporary variable
        // after store in temporary variable unset this key and value from shipingCost variable
        $adminShippingMethodId = $shippingCost["admin"] ?? 0;
        unset($shippingCost["admin"]);

        // return an array with all vendor and admin shipping cost eloquent collection
        return [
            "vendor" => !empty($shippingCost) ? self::vendorShippingCharge($shippingCost) : collect([]),
            "admin" => self::adminShippingCharge($adminShippingMethodId),
        ];
    }

    private static function adminShippingCharge(int $id): ?AdminShippingMethod
    {
        return AdminShippingMethod::where("status_id", 1)
            ->where("id", $id)
            ->first();
    }

    private static function vendorShippingCharge($shippingMethods): Collection|array
    {
        $shippingMethodQuery = VendorShippingMethod::query();
        // run a loop for getting multiple shipping cost and for that I am using orWhere method
        foreach($shippingMethods as $vendorId => $methodId){
            $shippingMethodQuery->where([
                ["id" ,"=",$methodId] ,
                ["vendor_id" ,"=",$vendorId] ,
                ["status_id" ,"=",1]
            ]);
        }

        // return shippingMethod collection
        return $shippingMethodQuery->get();
    }
}