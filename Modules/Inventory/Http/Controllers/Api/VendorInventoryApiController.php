<?php

namespace Modules\Inventory\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Inventory\Http\Resources\VendorInventoryListResource;
use Modules\Product\Http\Traits\ProductGlobalTrait;

class VendorInventoryApiController extends Controller
{
    public function index()
    {
        $all_inventory_products = ProductGlobalTrait::fetch_inventory_product()
            ->with("product:id,brand_id,name,is_inventory_warn_able,min_purchase,max_purchase","product.brand")
            ->whereHas('product', function ($query){
                $query->when(request()->has('name'), function ($query){
                    $query->where("name","like", "%" . strip_tags(request()->name) . "%");
                });
                $query->where("vendor_id", auth('sanctum')->id());
            })
            ->when(request()->has("sku"), function ($query){
                $query->where("sku","like", "%" . strip_tags(request()->sku) . "%");
            })
            ->paginate();

        return VendorInventoryListResource::collection($all_inventory_products);
    }
}