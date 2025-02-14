<?php

namespace Modules\MobileApp\Http\Resources;

use Illuminate\Http\Request;use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "image" => render_image($this->logo,render_type: 'path'),
        ];
    }
}
