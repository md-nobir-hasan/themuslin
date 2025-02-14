<?php

namespace Modules\MobileApp\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\ChildCategory;


class ChildCategoryController extends Controller
{
    /* 
    * fetch all subcategory list from database
    */
    public function allChildCategory($sub_category)
    {
        $categories = ChildCategory::select('id', 'name','image_id','sub_category_id')
            ->with("image:id,path")
            ->where('status_id',1)
            ->where("sub_category_id", $sub_category)
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
            'childCategories' => $categories
        ]);
    }
    
    /* 
    * fetch subcategory
    */
    public function singleChildCategory($sub_category,$id)
    {
        if(empty($id)){
             return response()->json([
                'message' => __('provide a valid id')
            ])->setStatusCode(422);
        }

        $child_categories = ChildCategory::select('id', 'name','image_id','sub_category_id')
            ->with("image:id,path")
            ->where("sub_category_id", $sub_category)
            ->where('id',$id)->first();
        
        if(!is_null($child_categories)){
            $image_url = null;
            if(!empty($child_categories->image_id)){
                $image_url = render_image($child_categories->image,render_type: 'path');
            }

            unset($child_categories->image);
            $child_categories->image_url = $image_url ?  : null;
        }
       
        
        return response()->json([
            'childCategory' => $child_categories
        ])->setStatusCode(200);
    
    }
}
