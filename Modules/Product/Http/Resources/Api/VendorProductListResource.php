<?php

namespace Modules\Product\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorProductListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "image" => render_image($this->image, size: 'thumb', render_type: 'path'),
            "brand" => $this?->brand?->name ?? null,
            "stock" => $this->inventory->stock_count ?? 0,
            "status" => $this?->status?->name ?? null
        ];
    }
}
