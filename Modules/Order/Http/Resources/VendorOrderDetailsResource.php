<?php

namespace Modules\Order\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorOrderDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "order_id" => $this->order_id,
            "vendor_id" => $this->vendor_id,
            "total_amount" => $this->total_amount,
            "shipping_cost" => $this->shipping_cost,
            "tax_amount" => $this->tax_amount,
            "order_address_id" => $this->order_address_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "order_number" => $this->order_number,
            "order_status" => $this->orderTrack?->first()?->name ?? 'ordered',
            "payment_status" => $this->payment_status,
            "order_item_count" => $this->order_item_count,
            "order" => $this->order,
            "vendor" => $this->vendor,
            "vendorLogo" => render_image($this->vendor?->logo, render_type: 'path'),
            "products" => new VendorOrderDetailsProductsResource((object)[
                "products" => $this->product,
                "order_item" => $this->orderItem,
                "product_variant" => $this->productVariant,
            ])
        ];
    }
}
