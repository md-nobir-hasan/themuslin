<?php

namespace Modules\MobileApp\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"=> $this->id,
            "title"=> $this->title,
            "subtitle"=> $this->subtitle,
            "image"=> render_image($this->campaignImage,render_type: 'path'),
            "start_date"=> $this->start_date,
            "end_date"=> $this->end_date,
        ];
    }
}
