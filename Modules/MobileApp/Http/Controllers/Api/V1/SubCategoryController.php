<?php

namespace Modules\MobileApp\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\SubCategory;

class SubCategoryController extends Controller
{
    /* 
    * fetch all subcategory list from database
    */
    public function allSubCategory($category_id)
    {
        $categories = SubCategory::select('id', 'name','image_id','category_id')
            ->with("image:id,path")
            ->where('status_id',1)->where("category_id", $category_id)
            ->orderBy('name', 'asc')->get()
            ->transform(function($item){
                $image_url = null;
                if(!empty($item->image_id)){
                    $image_url = render_image($item->image, render_type: 'path');
                }
                $item->image_url = $image_url ?  : null;
                unset($item->image);
                return $item;
            });
        
        return response()->json([
            'subcategories' => $categories
        ]);
    }
    
    /* 
    * fetch subcategory
    */
    public function singleSubCategory($category_id,$id)
    {
        if(empty($id)){
             return response()->json([
                'message' => __('provide a valid id')
            ])->setStatusCode(422);
        }

        $categories = SubCategory::select('id', 'name','image_id','category_id')
            ->with("image:id,path")
            ->where("category_id", $category_id)
            ->where('id',$id)->first();
        
        if(!is_null($categories)){
            $image_url = null;
            if(!empty($categories->image_id)){
                $image_url = render_image($categories->image,render_type: 'path');
            }

            unset($categories->image);
            $categories->image_url = $image_url ?  : null;
        }
       
        
        return response()->json([
            'subcategory' => $categories
        ])->setStatusCode(200);
    
    }
}
