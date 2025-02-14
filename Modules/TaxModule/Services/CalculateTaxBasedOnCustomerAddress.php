<?php

namespace Modules\TaxModule\Services;

use App\Exceptions\NotArrayObjectException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\Modules\Product\Entities\_IH_Product_C;
use Modules\CountryManage\Entities\City;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use Modules\Product\Entities\Product;
use Modules\TaxModule\Base\StaticInstance;
use Throwable;

class CalculateTaxBasedOnCustomerAddress
{
    use StaticInstance;
    private array | object $productIds;
    private ?Collection $products;

    /**
     * @throws NotArrayObjectException
     */
    public function productIds(array|object $ids): static
    {
        // check exception and throw error
        if(!is_array($ids) && !is_object($ids)){
            throw new NotArrayObjectException();
        }

        // now store product ids into productIds property
        $this->productIds = $ids;

        // now return this (this) mean's this class instance
        return $this;
    }

    /**
     * @throws Throwable
     */
    public function customerAddress(?int $country,?int $state = null,?int $city = null): CalculateTaxBasedOnCustomerAddress
    {
        // check those data and process tax for customer
        // if country exists then get all taxes according to those product tax class options
        // if state exists then get all state taxes if state does not have any tax then, then return country tax
        // if city is empty then return state tax if state is empty then return country tax
        if (!empty($city) && !empty($state) && !empty($country)){
            $this->products = $this->cityTax($country, $state, $city);
        }elseif (!empty($state) && !empty($country)){
            $this->products = $this->stateTax($country, $state);
        }elseif(!empty($country)) {
            $this->products = $this->countryTax($country);
        }

        // now return this class instance by using $this
        return $this;
    }

    /**
     * @throws Throwable
     */
    public function generate(): object
    {
        // check eligibility for customerBillingAddress
        throw_if(!self::is_eligible(), new \Exception("Please change settings from admin panel for getting customer billing address tax"));
        if(empty($this->products)){
            return collect([]);
        }
        // this method will return all products collection
        return $this->products;
    }

    /**
     * @throws Throwable
     */
    private function throwEmptyProductException(): void
    {
        if(empty($this->productIds)){
            $this->productIds = [];
        }
        // check product ids property is empty or not if empty then throw error
//        throw_if(empty($this->productIds), new \Exception("You couldn't access tax information without calling productIds([your product ids]) this method"));
    }

    /**
     * @throws Throwable
     */
    private function getAvailableProducts(): mixed
    {
        // this method will throw exception if productIds property is empty
        $this->throwEmptyProductException();
        // now get all country tax from given product ids
        return Product::select(["id", "name","tax_class_id","vendor_id"])
            ->without(["image","badge","uom","uom.unit"])
            ->whereIn("id", $this->productIds);
    }

    /**
     * @throws Throwable
     */
    private function countryTax(?int $country): Collection|_IH_Product_C|array
    {
        // call getAvailableProducts this method for and then call
        $products = $this->getAvailableProducts();

        return $products->whereHas("taxOptions")->withSum(["taxOptions" => function ($query) use ($country){
            $query->where("country_id", $country);
        }],"rate")->get();
    }

    /**
     * @throws Throwable
     */
    private function stateTax(?int $country, ?int $state): Collection|_IH_Product_C|array
    {
        // call getAvailableProducts this method for and then call
        $products = $this->getAvailableProducts();

        // check state tax product with two condition one is country_id second one is state_id
        $stateTaxProduct = $products->whereHas("taxOptions")
            ->withSum([
                "taxOptions" => function ($query) use ($country, $state) {
                    $query->where("country_id", $country);
                    $query->where("state_id", $state);
                    return $query;
                }
            ], "rate")
            // this having method will check collection as like where method do
            ->having("tax_options_sum_rate","!=", "NULL")->get();

        // check stateTaxProduct is getter then 0 then return products
        if($stateTaxProduct->isNotEmpty()){
            return $stateTaxProduct;
        }

        // now return country tax
        return $this->countryTax($country);
    }

    /**
     * @throws Throwable
     */
    private function cityTax(?int $country, ?int $state, ?int $city): Collection|_IH_Product_C|array
    {
        // call getAvailableProducts this method for and then call
        $products = $this->getAvailableProducts();

        // get product with some condition like (country_id, state_id, city_id)
        $cityTaxProduct = $products->whereHas("taxOptions")->withSum([
            "taxOptions" => function ($query) use ($country, $state, $city) {
                $query->where("country_id", $country);
                $query->where("state_id", $state);
                $query->where("city_id", $city);
            }
        ], "rate")->having("tax_options_sum_rate","!=", "NULL")->get();

        // count cityTaxProduct if it's getter then 0 then return cityTaxProducts
        if($cityTaxProduct->isNotEmpty()){
            return $cityTaxProduct;
        }

        // now return stateTax
        return $this->stateTax($country, $state);
    }

    // this method will return boolean value if true then go forward for next condition
    public static function is_eligible(): bool
    {
        return get_static_option("tax_system") == "advance_tax_system" &&
            get_static_option("calculate_tax_based_on") == "customer_billing_address" &&
            get_static_option("prices_include_tax") == "no";
    }

    // this method will return boolean value if true then go forward for next condition
    public static function is_eligible_inclusive(): bool
    {
        return get_static_option("tax_system") == "advance_tax_system" &&
            get_static_option("calculate_tax_based_on") == "customer_billing_address";
    }
}