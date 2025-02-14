<?php

namespace Modules\Campaign\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\MobileApp\Http\Resources\Api\MobileFeatureProductResource;

class VendorCampaignDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => render_image($this->campaignImage, size: 'thumb',render_type: 'path'),
            'end_date' => $this->end_date->format("Y-m-d h:i:s"),
            'status' => $this->status,
            'products' => MobileFeatureProductResource::collection($this->product)
        ];
    }
}
