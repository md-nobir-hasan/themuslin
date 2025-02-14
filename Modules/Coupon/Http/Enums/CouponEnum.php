<?php

namespace Modules\Coupon\Http\Enums;

class CouponEnum
{
    /**
     * Return values for discount_on field
     */
    public static function discountOptions()
    {
        return [
            'all' => __('All Products'),
            'category' => __('Category'),
            'subcategory' => __('Subcategory'),
            'childcategory' => __('Child category'),
            'product' => __('Product'),
            'shipping' => __('Shipping')
        ];
    }
}
