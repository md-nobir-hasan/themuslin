<?php

namespace App\Enums;

class ShippingEnum
{
    /**
     * Show as option in shipping method create modal
     */
    public static function shippingMethods()
    {
        return [
            'flat_rate' => __('Flat Rate'),
            'free_shipping' => __('Free Shipping'),
            'local_pickup' => __('Local Pickup')
        ];
    }

    /**
     * Applies to setting_method_options
     */
    public static function settingPresets()
    {
        return [
            'none' => __('N/A'),
            'min_order' => __('Minimum order amount'),
            'min_order_or_coupon' => __('Minimum order amount OR a coupon'),
            'min_order_and_coupon' => __('Minimum order amount AND a coupon'),
        ];
    }

    public static function taxStatus()
    {
        return [
            '1' => __('Taxable'),
            '0' => __('None'),
        ];
    }

    public static function publishStatus()
    {
        return [
            '1' => __('Active'),
            '0' => __('Disable'),
        ];
    }
}
