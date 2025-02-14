<?php

namespace Modules\Vendor\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminWalletGatewayResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "field" => unserialize($this->filed),
            "status_id" => $this->status_id
        ];
    }
}
