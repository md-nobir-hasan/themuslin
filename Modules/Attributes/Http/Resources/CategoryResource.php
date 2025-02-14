<?php

namespace Modules\Attributes\Http\Resources;

use App\Http\Resources\SubCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public static $wrap = 'category';

    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $sub_category = [];
        if(!empty($this?->subcategory)){
            $sub_category["sub_categories"] = SubCategoryResource::collection($this->subcategory);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => render_image($this->image,render_type: 'path'),
        ] + $sub_category;
    }

    public function with($request): array
    {
         return [
             "success" => true,
         ];
    }
}
