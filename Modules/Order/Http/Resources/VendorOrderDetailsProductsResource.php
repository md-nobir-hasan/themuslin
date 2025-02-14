<?php

namespace Modules\Order\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorOrderDetailsProductsResource extends JsonResource
{
    public function toArray($request)
    {
        $products = [];
        foreach($this->order_item as $item):
            $product = $this->products->find($item->product_id);
            $variant = $this->product_variant->find($item->variant_id);

            $variants = [];
            if($variant):
                if($variant->productColor):
                    $variants["color"] = $variant->productColor->name;
                endif;
                if($variant->productSize):
                    $variants["size"] = $variant->productSize->name;
                endif;

                $variants["attributes"] = [];
                foreach($variant->attribute as $attr):
                    $variants["attributes"][$attr->attribute_name] = $attr->attribute_value;
                endforeach;
            endif;

            $products[] = [
                "id" => $item->product_id,
                "image" => render_image($product->image, render_type: 'path'),
                "name" => $product->name,
                "product_variant" => $variants,
                "quantity" => $item->quantity,
                "price" => $item->price,
            ];
        endforeach;

        return $products;
    }
}
