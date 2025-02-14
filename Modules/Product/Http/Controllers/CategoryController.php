<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\ChildCategory;
use Modules\Attributes\Entities\SubCategory;

class CategoryController extends Controller
{
    public function getCategory(): JsonResponse
    {
        // need to fetch all categories and return as laravel eloquent object
        $categories = Category::all();

        $options = view("product::category.option", compact("categories"));
        return response()->json(["success" => true,"type" => "success","html" => $options]);
    }

    public function getSubCategory(Request $req)
    {
        // fetch sub category from category
            // -> SubCategory::where('category_id', $id)->get()
        $categories = SubCategory::where("category_id" , $req->category_id)->get();

        // load view file for select option
        $options = view("product::category.option", compact("categories"))->render();
        return response()->json(["success" => true,"type" => "success","html" => $options]);
    }

    public function getChildCategory(Request $req)
    {
        // fetch sub category from category
        //-> SubCategory::where('category_id', $id)->get()
        $categories = ChildCategory::where("sub_category_id" , $req->sub_category_id)->get();
        // load view file for select option
        $options = view("product::category.option", compact("categories"))->render();
        return response()->json(["success" => true,"type" => "success","html" => $options]);
    }
}
