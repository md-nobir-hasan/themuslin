<?php

namespace App\Helpers;

use Modules\Product\Entities\Product;

class CartAdminHelper {
    public static function addAllItemsToCart($request)
    {
        $all_products = $request->products;

        if (!is_array($all_products) || !count($all_products)) {
            return json_encode([]);
        }

        foreach ($request->count_arr as $count) {
            $product_attributes = isset($request->product_attributes[$count]) ? $request->product_attributes[$count] : [];
            $products_count = isset($request->products_count[$count]) ? $request->products_count[$count] : 1;

            foreach ($product_attributes as $id => $attributes) {
                $quantity = $products_count;

                // dd($products_count);

                if (is_string($attributes)) {
                    $attributes = json_decode($attributes, true);
                    CartHelper::add((int) $id, (int) $quantity, $attributes);
                } else if (is_array($attributes)) {
                    $product = Product::find($id);
                    $attribute = [];
                    $attribute['price'] = optional($product)->sale_price;
                    foreach ($attributes as $key => $value) {
                        $attr_arr = json_decode($value, true);
                        $attribute[$key] = $attr_arr[$key];
                        $attribute['price'] = $attribute['price'] + $attr_arr['price'];
                    }
                    CartHelper::add((int) $id, (int) $quantity, $attribute);
                }
            }
        }

        // foreach ($all_products as $key => $product_id) {
        //     $attribute = [];
        //     $product = Product::find($product_id);

        //     if ($request->product_attributes) {
        //         foreach ($request->product_attributes as $count => $product_attributes) {
        //             $attribute['price'] = optional($product)->sale_price;

        //             if (isset($product_attributes[$product_id])) {
        //                 $all_attributes = $product_attributes[$product_id];
        
        //                 foreach ($all_attributes as $type => $attr_json) {
        //                     $attribute_arr = json_decode($attr_json, true);
        
        //                     $attribute[$type] = $attribute_arr[$type];
        //                     $attribute['price'] = $attribute['price'] + $attribute_arr['price'];
        //                 }
        //             }
        
        //             CartHelper::add($product_id, (int) $request->products_count[$product_id], $attribute);
        //         }
        //     }
        // }

        return CartHelper::getItems();
    }
}


/**
    "product_attributes" => array:2 [▼
        1 => array:1 [▼
            33 => array:2 [▼
                "Color" => "{"id":33,"Color":"Green","price":"2.75"}"
                "Size" => "{"id":33,"Size":"M","price":"0"}"
            ]
        ]
        5 => array:1 [▼
            33 => array:2 [▼
                "Color" => "{"id":33,"Color":"Blue","price":"0"}"
                "Size" => "{"id":33,"Size":"XL","price":"2"}"
            ]
        ]
    ]
*/