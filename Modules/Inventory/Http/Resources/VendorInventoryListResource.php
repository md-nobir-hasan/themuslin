<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorInventoryListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->product->id,
            "name" => $this->product->name,
            "sku" => $this->sku,
            "brand" => $this->product->brand?->name,
            "stock" => $this->stock_count,
            "sold" => $this->sold_count,
            "is_warn_able_inventory" => $this->product->is_inventory_warn_able,
            "minimum_warning_count" => get_static_option("stock_threshold_amount")
        ];
    }
}
