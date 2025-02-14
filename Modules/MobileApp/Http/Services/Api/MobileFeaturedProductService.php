<?php

namespace Modules\MobileApp\Http\Services\Api;

use Modules\MobileApp\Entities\MobileFeaturedProduct;
use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Product\_IH_Product_C;

class MobileFeaturedProductService
{
    public static function get_product(): Collection|array|_IH_Product_C|null
    {
        $selectedProduct = MobileFeaturedProduct::first();
        $product = addonProductInstance();
        $ids = json_decode($selectedProduct->ids);

        if($selectedProduct->type == 'product'){
            return $product->whereIn("id",$ids)->get();
        }elseif ($selectedProduct->type == 'category'){
            return $product->whereHas("category", function ($query) use ($ids) {
                $query->whereIn("categories.id", $ids);
            })->get();
        }

        return [];
    }
}