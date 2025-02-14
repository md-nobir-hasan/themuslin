<?php

namespace Modules\Campaign\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $campaignImage
 * @property int $id
 * @property $title
 * @property mixed $end_date
 * @property string $status
 */
class VendorCampaignListResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => render_image($this->campaignImage, size: 'thumb',render_type: 'path'),
            'end_date' => $this->end_date->format("Y-m-d h:i:s"),
            'status' => $this->status
        ];
    }
}
