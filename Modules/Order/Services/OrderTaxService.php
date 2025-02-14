<?php

namespace Modules\Order\Services;

use Modules\ShippingModule\Http\ShippingZoneServices;

class OrderTaxService
{
    public static function taxAmount(int $country,?int $state_id,?int $city){
        $countryTax = ShippingZoneServices::getMethods($country, 'country');
        $stateTax = ShippingZoneServices::getMethods($state_id, 'state');

        return $stateTax->tax_amount ? $stateTax->tax_amount : $countryTax->tax_amount ?? 0;
    }
}