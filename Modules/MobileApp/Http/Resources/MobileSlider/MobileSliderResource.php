<?php

namespace Modules\MobileApp\Http\Resources\MobileSlider;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileSliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];

        if(!empty($this->campaign)){
            $data = ["campaign" => $this->campaign, "category" => null];
        }elseif(!empty($this->category)){
            $data = ["campaign" => null, "category" => $this->sliderCategory?->name];
        }else{
            $data = ["campaign" => null, "category" => null];
        }

        return [
            "title" => $this->title,
            "description" => $this->description,
            "image" => get_attachment_image_by_id($this->image_id)["img_url"] ?? null,
            "button_url" => $this->url,
            "button_text" => $this->button_text,
        ] + $data;
    }
}
