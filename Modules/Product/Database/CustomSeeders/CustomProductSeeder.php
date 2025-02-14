<?php

namespace Modules\Product\Database\CustomSeeders;

use App\MediaUpload;
use Exception;
use Modules\AdminManage\Entities\Admin;
use Modules\Attributes\Entities\DeliveryOption;
use Modules\Attributes\Entities\Unit;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductChildCategory;
use Modules\Product\Entities\ProductCreatedBy;
use Modules\Product\Entities\ProductDeliveryOption;
use Modules\Product\Entities\ProductGallery;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductSubCategory;
use Modules\Product\Entities\ProductTag;
use Modules\Product\Entities\ProductUom;
use Modules\Product\Entities\Tag;
use Modules\Vendor\Entities\Vendor;

class CustomProductSeeder
{
    /**
     * @throws Exception
     */
    public static function seed($products, $category): void
    {
        foreach($products as $product){

            $guards = ['admin','vendor'];
            $rand = rand(0,1);
            $guard = $guards[$rand];

            $admin = null;
            $vendor = null;

            if($guard == 'admin'){
                $admin = Admin::select('id')->inRandomOrder()->first();
                $product += ['admin_id' => $admin->id];
            }elseif($guard == 'vendor'){
                $vendor = Vendor::select('id')->whereBetween('id',[1,3])->inRandomOrder()->first();
                $product += ['vendor_id' => $vendor->id];
            }

            $c_product = Product::create($product);
            $c_product->metaData()->create(self::generateMetaData($c_product));
            $categories_array = self::generateCategory($c_product, $category);

            ProductCreatedBy::create(self::generateCreatedBy($c_product, $guard, $admin, $vendor));

            ProductCategory::create($categories_array['category']);
            !empty($categories_array['sub_category']) ?
                ProductSubCategory::create($categories_array['sub_category'])
            : '';

            !empty($categories_array['child_category']) ?
                ProductChildCategory::insert($categories_array['child_category'])
            : '';

            // create product inventory
            ProductInventory::create(self::generateInventory($c_product));

            // insert delivery options
            $deliveryOptions = self::generateDeliveryOptions($c_product);
            !empty($deliveryOptions) ?
                ProductDeliveryOption::insert($deliveryOptions)
            : '';

            // insert product gallery images
            $productGalleries = self::generateGallery($c_product);
            !empty($productGalleries) ?
                ProductGallery::insert($productGalleries)
            : '';

            // insert product tags
            $productTags = self::generateTags($c_product);
            !empty($productTags) ?
                ProductTag::insert($productTags)
            : '';

            // insert product uom
            $productUom = self::generateUom($c_product);
            !empty($productUom) ?
                ProductUom::create($productUom)
            : '';
        }
    }

    public static function generateCreatedBy($product, $guard, $admin = null, $vendor = null): array
    {
        $createdBy = [];

        if($guard == 'admin'){
            $createdBy = [
                "created_by_id" => $admin->id,
                "guard_name" => 'admin',
            ];
        }elseif ($guard == 'vendor'){
            $createdBy = [
                "created_by_id" => $vendor->id,
                "guard_name" => 'admin',
            ];
        }

        return [
            "product_id" => $product->id,
            "updated_by" => null,
            "updated_by_guard" => null,
            "deleted_by" => null,
            "deleted_by_guard" => null
        ] + $createdBy;
    }

    public static function generateMetaData($product): array
    {
        $product_meta_tags = str_replace(' ', ' , ', $product->name);
        $product_meta_description = strip_tags($product->description);

        return [
            'meta_title' => $product->name,
            'meta_tags' => $product_meta_tags,
            'meta_description' => $product_meta_description,
            'facebook_meta_tags' => $product_meta_tags,
            'facebook_meta_description' => $product_meta_description,
            'facebook_meta_image' => null,
            'twitter_meta_tags' => $product_meta_tags,
            'twitter_meta_description' => $product_meta_description,
            'twitter_meta_image' => null
        ];
    }

    public static function generateCategory($product, $category): array
    {
        $categoryArr = [
            "category_id" => $category->id,
            "product_id" => $product->id
        ];

        $subCategory = $category->subcategory?->random()->first();

        $subCategoryArr = !empty($subCategory) ?
            ["sub_category_id" => $subCategory->id,"product_id" => $product->id]
        : [];

        $childCategoriesArr = [];
        $childCategoryCount = $subCategory->childcategory->count();
        $randInt = rand(0, $childCategoryCount ?? 1);
        $childCategories = $subCategory->childcategory->take($randInt) ?? [];

        // check subcategory exists and childCategory count is getter-than 0 and randInt should be getter-than 0
        if(!empty($subCategory) && $childCategories->isNotEmpty() && $randInt > 0){
            foreach ($childCategories as $childCategory){
                // this condition will check rand int is bigger than one
                if($randInt > 0)
                    $childCategoriesArr[] = ["child_category_id" => $childCategory->id,"product_id" => $product->id];
                // decrease randInt
                $randInt--;
            }
        }

        return [
            "category" => $categoryArr,
            "sub_category" => $subCategoryArr,
            "child_category" => $childCategoriesArr ?? []
        ];
    }

    /**
     * @throws Exception
     */
    public static function generateInventory($product): array
    {
        // this method will be responsible for only generate an array for inventory model
        return [
            "product_id" => $product->id,
            "sku" => createSlug($product->name,'ProductInventory',true,'Product','sku'),
            "stock_count" => random_int(500,1000),
            "sold_count" => random_int(5,200)
        ];
    }

    public static function generateDeliveryOptions($product): array
    {
        $deliveryOptionsArr = [];
        $deliveryOptions = DeliveryOption::select('id')->take(rand(0,5));

        foreach($deliveryOptions as $deliveryOption){
            $deliveryOptionsArr[] = [
                "product_id" => $product->id,
                "delivery_option_id" => $deliveryOption->id
            ];
        }

        return $deliveryOptionsArr;
    }

    public static function generateGallery($product): array
    {
        $galleryImageArr = [];
        $galleryImages = MediaUpload::select('id')->inRandomOrder()->take(rand(0,5));

        foreach($galleryImages as $galleryImage){
            $galleryImageArr[] = [
                "product_id" => $product->id,
                "image_id" => $galleryImage->id
            ];
        }

        return $galleryImageArr;
    }

    public static function generateTags($product): array
    {
        $tagsArr = [];
        $tags = Tag::select('id')->inRandomOrder()->take(rand(0,5));

        foreach($tags as $tag){
            $tagsArr[] = [
                "product_id" => $product->id,
                "tag_name" => $tag->tag_text
            ];
        }

        return $tagsArr;
    }

    public static function generateUom($product): array
    {
        $unit = Unit::select('id')->inRandomOrder()->first();

        return [
            "product_id" => $product->id,
            "unit_id" => $unit->id,
            "quantity" => rand(1,12)
        ];
    }
}