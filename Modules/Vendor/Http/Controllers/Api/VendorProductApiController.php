<?php

namespace Modules\Vendor\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;

class VendorProductApiController extends Controller
{
    public function productList(){
        // first need to get all product
        $products = Product::with("image")->get();

        return $products;
    }
}