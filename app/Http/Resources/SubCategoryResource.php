<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $child_category = [];
        if(!empty($this->childcategory)){
            $child_category["child_categories"] = ChildCategoryResource::collection($this->childcategory);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => render_image($this->image, render_type: 'path'),
        ] + $child_category;
    }
}
