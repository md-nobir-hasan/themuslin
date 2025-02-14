<?php

namespace Modules\MobileApp\Http\Resources;

use Illuminate\Http\Request;use Illuminate\Http\Resources\Json\JsonResource;

class PaymentGatewayResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "name" => $this->name,
            "image" => render_image($this->oldImage,render_type: 'path'),
//            "description" => $this->description,
            "status" => $this->status,
            "test_mode" => $this->test_mode,
            "credentials" => json_decode($this->credentials)
        ];
    }
}
