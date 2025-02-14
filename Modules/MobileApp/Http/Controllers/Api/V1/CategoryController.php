<?php

namespace Modules\MobileApp\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /* 
    * fetch all country list from database
    */
    public function allCategory()
    {
        $categories = Category::query()->select("id","name","image_id")->with("image")
            ->where('status_id', 1)
            ->whereHas("product")
            ->orderBy('name', 'asc')->get()
            ->transform(function($item){
                $image_url = null;
                if(!empty($item->image_id)){
                    $image_url = render_image($item->image, render_type: 'path');
                }

                $item->image_url = $image_url ?  : null;
                unset($item->image);
                unset($item->image_id);
                return $item;
            });

        return response()->json([
            "categories" => $categories,
            "success" => true,
        ]);
    }
    
    /* 
    * fetch all state list based on provided country id from database
    */
    public function singleCategory($id)
    {
        if(empty($id)){
             return response()->json([
                'message' => __('provide a valid id')
            ])->setStatusCode(422);
        }

        $categories = Category::select('id', 'name','image_id')->with("image")->where('id',$id)->first();

        $image_url = null;
        if(!empty($categories->image_id)){
            $image_url = render_image($categories->image, render_type: 'path');
        }
        $categories->image_url = $image_url ?  : null;
        unset($categories->image);


        return response()->json([
            "category" => $categories,
            "success" => true,
        ]);
    }

    public function allCategories(){
        // before change please mind it this method is also used on vendor api
        return CategoryResource::collection(Category::with("image","subcategory","subcategory.image","subcategory.childcategory","subcategory.childcategory.image")->get());
    }
}
