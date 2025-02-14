<?php

namespace Modules\MobileApp\Http\Controllers;

use Modules\Attributes\Entities\Category;
use Modules\MobileApp\Entities\MobileFeaturedProduct;
use Modules\MobileApp\Http\Requests\StoreMobileFeaturedProductRequest;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Illuminate\Routing\Controller;

class MobileFeaturedProductController extends Controller
{
    public function index()
    {
        $mobileFeaturedProducts = MobileFeaturedProduct::all();

        return view("mobileapp::mobile-featured-product.list",compact("mobileFeaturedProducts"));
    }

    public function create(){
        // fetch all categories
        // fetch all products
        $categories = Category::select("id","name")->get();
        $products = Product::select("id","name")->get();
        $selectedProduct = MobileFeaturedProduct::first();

        return view("mobileapp::mobile-featured-product.create",compact(["products","categories","selectedProduct"]));
    }

    public function store(StoreMobileFeaturedProductRequest $request){
        $bool = MobileFeaturedProduct::updateOrCreate(["id" => 1],$request->validated());

        return back()->with(['msg' => __('Updated Feature Product...'), 'type' => 'success']);
    }

    public function edit(){
        return "This is edit view";
    }

    public function update(){
        return "This is update view";
    }

    public function destroy(){
        return "This is destroy method";
    }
}
