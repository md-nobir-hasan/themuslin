<?php

namespace App\Helpers;

use Modules\Product\Entities\InventoryDetailsAttribute;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductInventoryDetails;

class ProductRequestHelper
{
    public static function getRequestedProductAttributes($product, $pid_id) : array
    {
        $attributes = [];
        $product_price = 0;
        $additional_price = 0;

        $inventory_details = ProductInventoryDetails::find($pid_id);

        if ($inventory_details) {
            $inventory_details_attributes = InventoryDetailsAttribute::where('inventory_details_id', $inventory_details->id)->get();

            if ($inventory_details_attributes) {
                foreach ($inventory_details_attributes as $key => $inventory_attribute) {
                    $attributes[$inventory_attribute->attribute_name] = $inventory_attribute->attribute_value;
                }
            }

            $product_price = optional($product)->sale_price ?? 0;
            $additional_price = $inventory_details->additional_price;
            $attributes['price'] = $product_price + $additional_price;
        }

        return [
            'attributes' => $attributes,
            'price' => $product_price,
            'additional_price' => $additional_price
        ];
    }

    public static function getShippingOptionMessage($setting_preset, $minimum_order_amount) : string
    {
        $message = __('Minimum total order amount has to be') . ' ' . $minimum_order_amount;

        if ($setting_preset === 'min_order_or_coupon') {
            $message .= ' ' . __('or a valid coupon has to be given.');
        } elseif ($setting_preset === 'min_order_and_coupon') {
            $message .= ' ' . __('and a valid coupon has to be given.');
        }

        return $message;
    }

    public static function getProductAttributesFromIdForCart($id)
    {
        $product_details = ProductInventoryDetails::where('id', $id)->with('includedAttributes')->first();
        $product = Product::find($product_details->product_id);

        if (is_null($product_details)) return null;

        $additional_price = (int) $product_details->additional_price;
        $attributes = [];

        if ($product_details->includedAttributes) {
            foreach ($product_details->includedAttributes as $included_attribute) {
                $attributes[$included_attribute['attribute_name']] = $included_attribute['attribute_value'];
                $attributes['price'] = $product->sale_price + $additional_price;
            }
        }

        return $attributes;
    }
}
