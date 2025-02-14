<?php

namespace Modules\MobileApp\Http\Resources;

use Illuminate\Http\Request;use Illuminate\Http\Resources\Json\JsonResource;

class MobileIntroResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "title" => $this->title,
            "description" => $this->description,
            "image" => render_image($this->image, render_type: 'path')
        ];
    }
}
