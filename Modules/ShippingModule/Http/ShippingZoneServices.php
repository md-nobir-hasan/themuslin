<?php

namespace Modules\ShippingModule\Http;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use stdClass;

class ShippingZoneServices
{
    public static function getMethods($id, $type): ?stdClass
    {
        return self::getDataTypeWise($id, $type);
    }

    private static function getDataTypeWise($id,$type): ?stdClass
    {
        return match ($type){
            "country" => self::getCountryData($id),
            "state" => self::getStateData($id),
            "city" => self::getCityData($id),
            default => null
        };
    }

    private static function getCountryData($id): stdClass
    {
        /**
         * TODO:: first check country is exist or not if exist then get all data from
         *
         * Todo:: {
         *  all_states: collection,
         *  tax_amount: number,
         *  all_methods: collection,
         *  get_default_method: collection
         * TODO:: }
         */

        return self::countryShippingMethods($id);
    }

    public static function getStateData($id): stdClass
    {
        /**
         * TODO:: first check country is exist or not if exist then get all data from
         *
         * TODO:: {
         *  all_states: collection,
         *  tax_amount: number,
         *  all_methods: collection,
         *  get_default_method: collection
         * TODO:: }
         */
        $state = State::with(["stateShippingMethods","stateTax","cities"])->find($id);

        $methods = $state?->stateShippingMethods->transform(function ($item) use ($state) {
            $item->preset_name = self::preset_name($item->setting_preset, $item->minimum_order_amount);
            return $item;
        });

        $obj = new stdClass();
        $obj->tax_amount = $state->stateTax?->tax_percentage ?? 0;
        $obj->cities = $state->cities ?? [];

        return $obj;
    }

    private static function countryShippingMethods($countryId): stdClass
    {
        $country = Country::with(["countryTax","shippingMethods" => fn($query) =>
        $query->select("shipping_method_options.id","shipping_methods.is_default","title","status","tax_status","setting_preset","cost","minimum_order_amount","coupon")
            ->where("status",1)
        ])->find($countryId);

        $shippingMethods = $country->shippingMethods->transform(function ($item) {
            $item->preset_name = self::preset_name($item->setting_preset, $item->minimum_order_amount);
            return $item;
        });

        $obj = new stdClass();

        $obj->states = State::select("id","name")->where("country_id", $countryId)->get()->toArray();
        $obj->tax_amount = $country?->countryTax?->tax_percentage ?? 0;

        return $obj;
    }

    private static function preset_name($settingPreset, $amount): array|string|Translator|Application|null
    {
        return match ($settingPreset){
            'none' => '',
            'min_order' => __('Minimum order amount '.$amount ?? ''),
            'min_order_or_coupon' => __('Minimum order amount '.$amount.' OR a coupon'),
            'min_order_and_coupon' => __('Minimum order amount '.$amount.' AND a coupon'),
        };
    }

    private static function getCityData($id)
    {
        /**
         * TODO:: first check country is exist or not if exist then get all data from
         *
         * TODO:: {
         *  all_states: collection,
         *  tax_amount: number,
         *  all_methods: collection,
         *  get_default_method: collection
         * TODO:: }
         */
        $state = State::with(["stateShippingMethods","stateTax","cities"])->find($id);

        $methods = $state?->stateShippingMethods->transform(function ($item) use ($state) {
            $item->preset_name = self::preset_name($item->setting_preset, $item->minimum_order_amount);
            return $item;
        });

        $obj = new stdClass();
        $obj->tax_amount = $state->stateTax?->tax_percentage ?? 0;
        $obj->cities = $state->cities;

        return $obj;
    }
}