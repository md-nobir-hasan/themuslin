<?php

namespace Modules\TaxModule\Traits;

trait TaxCalculatorTrait
{
    public function taxSystem(){
        return get_static_option("tax_system");
    }

    public function priceIncludeTax(){
        return get_static_option("prices_include_tax");
    }

    public function calculateTaxBasedOn(){
        return get_static_option("calculate_tax_based_on");
    }

    public function shippingTaxClassId(){
        return get_static_option("shipping_tax_class");
    }

    public function taxRoundAtSubtotal(){
        return get_static_option("tax_round_at_subtotal");
    }

    public function displayPriceInTheShop(){
        return get_static_option("display_price_in_the_shop");
    }

    public function displayTaxTotal(){
        return get_static_option("display_tax_total");
    }
}