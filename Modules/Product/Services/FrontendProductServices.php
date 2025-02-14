<?php


namespace Modules\Product\Services;

use Exception;
use Modules\Attributes\Entities\Brand;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\Size;
use Modules\Attributes\Entities\Unit;
use Modules\Attributes\Http\Resources\CategoryResource;
use Modules\MobileApp\Http\Resources\BrandResource;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Http\Traits\ProductGlobalTrait;

class FrontendProductServices
{
    use ProductGlobalTrait;

    /**
     * @throws Exception
     */
    public static function productSearch($request, $req_route): array
    {
        $route = null;

        if(!empty($req_route)){
            $route = $req_route;
        }

        return (new self)->search($request, $route,'frontend');
    }

    public static function shopPageSearchContent(): array
    {
        return [
            "all_category" => CategoryResource::collection(Category::with("image","subcategory","subcategory.image","subcategory.childcategory","subcategory.childcategory.image")->get()),
            "all_units" => Unit::all(),
            "all_colors" => Color::whereHas("product")->get(),
            "all_sizes" => Size::whereHas("product")->get(),
            "all_brands" => BrandResource::collection(Brand::with("logo")->whereHas("product")->get()),
            "max_price" => Product::query()->max('price'),
            "min_price" => Product::query()->min('sale_price'),
            "item_style" =>['grid','list'],
        ];
    }
}