<?php

namespace Modules\TaxModule\Services;

use Modules\TaxModule\Traits\TaxCalculatorTrait;

class CalculateTaxServices
{
    use TaxCalculatorTrait;

    private ?CalculateTaxServices $instance = null;

    public static function init(): ?CalculateTaxServices
    {
        $self = new self();
        if(!is_null($self->instance)){
            return $self->instance;
        }

        return $self;
    }

    // first method will check product price if admin enable advance tax module with prices entered with tax then this method will returned product price with tax

    /**
     * @param float|int|null $price
     * @param object|float $product
     * @param string $for
     * @return float|int|null
     */
    public static function productPrice(float|int|null $price, object|float $product, string $for = "product") : float|int|null
    {
        // check price is null than return
        if(is_null($price))
            return null;

        // create a instance of this class first
        $init = self::init();
        // first need to get all information related to static options
        // check is prices entered with tax is enable or not

        if($init->taxSystem() == 'advance_tax_system' && $init->priceIncludeTax() == 'yes' && $for == 'product'){
//            $price = $price + calculatePercentageAmount($price, $product->tax_options_sum_rate);   Previous calculation
            $price = $price + calculatePercentageAmount($price, 0);
        }elseif($init->taxSystem() == 'advance_tax_system' && $for == 'shipping'){
            $price = $price + calculatePercentageAmount($price, $product);
        }elseif(
            $init->taxSystem() == 'advance_tax_system'
            && get_static_option("calculate_tax_based_on") == 'customer_billing_address'
            && $init->priceIncludeTax() == 'no' && $for == 'percentage'
        ){
            $price = calculatePercentageAmount($price, $product->tax_options_sum_rate);
        }

        if(get_static_option("tax_round_at_subtotal") == 1){
            return toFixed($price, 0);
        }

        return $price;
    }

    public static function pricesEnteredWithTax($for = "product"): bool
    {
        // create a instace of this class first
        $init = self::init();
        // first need to get all information related to static options
        // check is prices entered with tax is enable or not
        if($init->taxSystem() == 'advance_tax_system' && $init->priceIncludeTax() == 'yes' && $for == 'product'){
            return true;
        }elseif($init->taxSystem() == 'advance_tax_system' && $for == 'shipping'){
            return true;
        }

        return false;
    }


    public static function isPriceEnteredWithTax(): bool
    {
        // create a instace of this class first
        $init = self::init();
        return $init->taxSystem() == 'advance_tax_system' && $init->priceIncludeTax() == 'yes';
    }
}