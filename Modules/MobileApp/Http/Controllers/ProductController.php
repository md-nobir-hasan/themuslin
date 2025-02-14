<?php

namespace Modules\MobileApp\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\Brand;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\Size;
use Modules\Attributes\Entities\Unit;
use Modules\MobileApp\Http\Resources\Api\MobileFeatureProductResource;
use App\Http\Resources\ProductResource;
use App\StaticOption;
use Modules\Order\Entities\SubOrderItem;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductRating;
use Modules\Product\Entities\ProductTag;
use Modules\Product\Entities\ProductUnit;
use Modules\Product\Entities\ProductUom;
use Modules\Product\Entities\SaleDetails;
use Illuminate\Http\Request;
use Modules\Product\Http\Services\Api\ApiProductServices;
use Modules\Product\Services\FrontendProductServices;

class ProductController extends Controller
{

    public function search(Request $request){
        $all_products = ApiProductServices::productSearch($request, "api", "api", false);

        return MobileFeatureProductResource::collection($all_products);//->additional($additional);
    }

    public function productDetail($id){
        $product = Product::where('id', $id)
            ->with([
                'category:categories.id,name,image_id',
                'category.image:id,title,path,alt',
                'subCategory:sub_categories.id,name,image_id',
                'subCategory.image:id,title,path,alt',
                'childCategory:child_categories.id,name,image_id',
                'childCategory.image:id,title,path,alt',
                'tag',
                'brand',
                'color',
                'size',
                'campaign_product',
                'inventoryDetail' => function ($query){
                    $query->where("stock_count",">", 0);
                },
                'inventoryDetail.productColor',
                'inventoryDetail.productSize',
                'inventoryDetail.attribute',
                'reviews',
                'reviews.user:id,name,image',
                'inventory',
                'gallery_images',
                'productDeliveryOption',
                'campaign_sold_product',
                'vendor' => function ($item){
                    $item->withCount("product");
                    $item->whereHas("product", function ($query){
                        $query->where("status_id", 1);
                    });
                },
                'vendor.vendor_shop_info:id,vendor_id,logo_id',
                'vendor.vendor_shop_info.logo:id,title,alt,path',
                'vendor.product' => function ($item){
                    $item->withAvg("ratings", "rating");
                },
                'vendor.product.vendor',
                'vendor.product.subCategory',
                'vendor.product.childCategory',
                'vendor.product.category',
                'vendor.vendor_address' => function ($item){
                    $item->with("country");
                },
                'vendor.product.campaign_product',
                'vendor.product.ratings',
                'vendor.product.inventory',
                'vendor.product.campaign_sold_product',
                'vendor.product.badge',
                'vendor.product.uom',
            ])
            ->withAvg("ratings", 'rating')
            ->withCount("ratings")
            ->withSum("taxOptions", "rate")
            ->where("status_id", 1)
            ->first();

        if(empty($product)){
           return response()->json([
               "msg" => __("No product found")
           ],404);
        }

        $campaign_product = getCampaignProductById($product->id);
        $sale_price = $campaign_product ? $campaign_product->campaign_price : $product->sale_price;
        $deleted_price = $campaign_product ? $product->sale_price : $product->price;
        $campaign_percentage = $campaign_product ? getPercentage($product->sale_price, $sale_price) : false;
        $product->campaign_percentage = $campaign_percentage;
        $product->random_key = random_int(11111111,99999999) . $product->tax_options_sum_rate . random_int(111111111111111,999999999999999);
        $product->random_secret = random_int(111111111111111,999999999999999) . round($sale_price,0) . random_int(11111111,99999999);
        $product->sale_price = calculatePrice($sale_price, $product);
        $product->price = calculatePrice($deleted_price, $product);

        unset($product->tax_options_sum_rate);

        // remove shop-info from a collection
        if(!empty($product->vendor?->vendor_shop_info)){
            // those lines are for vendor logo
            $product->vendor->image = render_image($product?->vendor?->vendor_shop_info?->logo, render_type: 'path');
            unset($product->vendor->vendor_shop_info);
        }

        // write code for product category only add an image path into a category array

        if(!empty($product->category)){
            $product->category->categoryImage = render_image($product->category?->image , render_type: 'path');

            if($product->category?->image_id ?? false){
                unset($product->category->image_id);
            }

            if($product->category?->laravel_through_key ?? false){
                unset($product->category->laravel_through_key);
            }

            if($product->category?->image ?? false){
                unset($product->category->image);
            }
        }

        // write code for product subcategory only add an image path into a category array
        if (!empty($product->subCategory)){
            $product->subCategory->categoryImage = render_image($product->subCategory?->image ?? 0, render_type: 'path');
            if($product->subCategory->image_id ?? false){
                unset($product->subCategory->image_id);
            }

            if($product->subCategory?->laravel_through_key ?? false){
                unset($product->subCategory->laravel_through_key);
            }

            if($product->subCategory?->image ?? false){
                unset($product->subCategory->image);
            }
        }

        // write code for product sub category only add image path into category array
        $product->childCategory->transform(function ($item){
            $image = $item->image ?? 0;
            if($item->image ?? false){
                unset($item->image);
            }

            if($item->image_id ?? false){
                unset($item->image_id);
            }

            if($item->laravel_through_key){
                unset($item->laravel_through_key);
            }

            $item->image = render_image($image ?? 0 , render_type: 'path');
            return $item;
        });

        foreach($product->gallery_images as $gallery){
            $gallery->image = render_image($gallery,render_type: 'path');
            unset($gallery->id);
            unset($gallery->title);
            unset($gallery->path);
            unset($gallery->alt);
            unset($gallery->size);
            unset($gallery->dimensions);
            unset($gallery->user_id);
            unset($gallery->created_at);
            unset($gallery->updated_at);
            unset($gallery->vendor_id);
            unset($gallery->laravel_through_key);
        }

        // test
        $productImage = $product->image ?? 0;
        if($product->image ?? false){
            unset($product->image);
        }

        $product->image = render_image($productImage,render_type: "path");

        // get selected attributes in this product ( $available_attributes )
        $inventoryDetails = optional($product->inventoryDetail);
        $product_inventory_attributes = $inventoryDetails->toArray();

        /* *
         * ========================================================
         * Example of $product_inventory_attributes
         *
         * array:2 [▼
         *     0 => array:15 [▼
         *       'id" => 9
         *       "inventory_id" => 54
         *       "product_id" => 48
         *       "color" => "2"
         *       "size" => "1"
         *       "hash" => null
         *       "additional_price" => 971.0
         *       "image" => "382"
         *       "stock_count" => 92
         *       "sold_count" => 0
         *       "product_color" => array:6 [▼
         *             "id" => 2
         *             "name" => "Dark Green"
         *             "color_code" => "#08781a"
         *             "slug" => "dark-green"
         *             "created_at" => "2022-03-03T11:05:51.000000Z"
         *             "updated_at" => "2022-03-03T11:06:15.000000Z"
         *         ]
         *         "product_size" => array:6 [▼
         *             "id" => 1
         *             "name" => "Exatra Small"
         *             "size_code" => "xs"
         *             "slug" => "extra-small"
         *         ]
         *         "included_attributes" => array:3 [▼
         *             0 => array:7 [▼
         *                   "id" => 16
         *                   "product_id" => 48
         *                   "inventory_details_id" => 9
         *                   "attribute_name" => "Cheese"
         *                   "attribute_value" => "chereme"
         *             ]
         *         ]
         *      ]
         *   ]
         * ========================================================
         * */

        $all_included_attributes = array_filter(array_column($product_inventory_attributes, 'attribute', 'id'));
        $all_included_attributes_prd_id = array_keys($all_included_attributes);

        /* *
         * ========================================================
         * Example of $all_included_attributes
         *
         * array:2 [▼
         *     9 => array:3 [▼
         *       0 => array:7 [▼
         *         "id" => 16
         *         "product_id" => 48
         *         "inventory_details_id" => 9
         *         "attribute_name" => "Cheese"
         *         "attribute_value" => "cream"
         *         "created_at" => "2022-03-15T07:44:52.000000Z"
         *         "updated_at" => "2022-03-15T07:44:52.000000Z"
         *       ]
         *     ]
         *   ]
         * ========================================================
         * */

        $available_attributes = [];  // FRONTEND: All displaying attributes
        $product_inventory_set = []; // FRONTEND : attribute_store
        $additional_info_store = []; // FRONTEND : $additional_info_store

        foreach ($all_included_attributes as $id => $included_attributes) {
            $single_inventory_item = [];
            $single_inventory_item = [];
            $colorCode = [];
            foreach ($included_attributes as $included_attribute_single) {
                /**
                 * Example: (Only data representation, not in code)
                 *      selected_attributes = [
                 *          'Cheese' => ['Mozzarella', 'Cheddar', 'Parmesan'],
                 *          'Sauce' => ['Hot', 'Taco', 'Fish', 'Soy', 'Tartar']
                 *      ];
                 */

                $available_attributes[$included_attribute_single['attribute_name']][ $included_attribute_single['attribute_value']] = 1;

                // individual inventory item
                $single_inventory_item[$included_attribute_single['attribute_name']] = $included_attribute_single['attribute_value'];

                /* *
                 * ========================================================
                 * Example of $available_attributes
                 *
                 * array:3 [▼
                 *     "Cheese" => "cream"
                 *     "Color" => "Green"
                 *     "Size" => "M"
                 *   ]
                 * ========================================================
                 * */

                if (optional($inventoryDetails->find($id))->productColor) {
                    $single_inventory_item['Color'] = optional(optional($inventoryDetails->find($id))->productColor)->name;
                    $colorCode["color_code"] = optional(optional($inventoryDetails->find($id))->productColor)->color_code;
                }

                if (optional($inventoryDetails->find($id))->productSize) {
                    $single_inventory_item['Size'] = optional(optional($inventoryDetails->find($id))->productSize)->name;
                }
            }

            $item_additional_price = optional(optional($product->inventoryDetail)->find($id))->additional_price ?? 0;
            $item_additional_stock = optional(optional($product->inventoryDetail)->find($id))->stock_count ?? 0;
            $image = get_attachment_image_by_id(optional(optional($product->inventoryDetail)->find($id))->image)['img_url'] ?? '';

            $sorted_inventory_item = $single_inventory_item;
            ksort($sorted_inventory_item);

            $single_inventory_item["hash"] = md5(json_encode($sorted_inventory_item));
            $single_inventory_item += $colorCode;
            $product_inventory_set[] = $single_inventory_item;

            $additional_info_store[md5(json_encode($sorted_inventory_item))] = [
                'pid_id' => $id, // ProductInventoryDetails->id
                'additional_price' => $item_additional_price,
                'stock_count' => $item_additional_stock,
                'image' => $image,
            ];
        }


        $productColors = $product->color->unique();
        $productSizes = $product->size->unique();

        if ((empty($available_attributes) && !empty($product_inventory_attributes)) || count($all_included_attributes) < $product->inventoryDetail->count()) {
            $sorted_inventory_item = [];
            $product_id = $product_inventory_attributes[0]['id'];
            // check inventory color and size exists or not

            if (!empty($product->inventoryDetail)) {
                foreach ($product->inventoryDetail as $inventory) {
                    // if this inventory has attributes, then it will fire a continue statement
                    if (in_array($inventory->product_id, $all_included_attributes_prd_id)) {
                        continue;
                    }

                    $colorCode = [];
                    $single_inventory_item = [];

                    if (optional($inventoryDetails->find($product_id))->color) {
                        $single_inventory_item['Color'] = optional($inventory->productColor)->name;
                        $colorCode["color_code"] = optional($inventory->productColor)->color_code;
                    }

                    if (optional($inventoryDetails->find($product_id))->size) {
                        $single_inventory_item['Size'] = optional($inventory->productSize)->name;
                    }

                    $item_additional_price = optional($inventory)->additional_price ?? 0;
                    $item_additional_stock = optional($inventory)->stock_count ?? 0;
                    $image = get_attachment_image_by_id(optional($inventory)->image)['img_url'] ?? '';

                    $sorted_inventory_item = $single_inventory_item;
                    ksort($sorted_inventory_item);

                    $single_inventory_item["hash"] = md5(json_encode($sorted_inventory_item));
                    $single_inventory_item += $colorCode;
                    $product_inventory_set[] = $single_inventory_item;

                    $additional_info_store[md5(json_encode($sorted_inventory_item))] = [
                        'pid_id' => $product_id,
                        'additional_price' => $item_additional_price,
                        'stock_count' => $item_additional_stock,
                        'image' => $image,
                    ];
                }
            }
        }

        $available_attributes = array_map(fn($i) => array_keys($i), $available_attributes);

        $product->reviews->transform(function ($item){
            $item->user->image = render_image($item->user->profile_image, render_type: 'path');
            unset($item->user->profile_image);
            return $item;
        });

        $sub_category_arr = json_decode($product->sub_category_id, true);
        $ratings = ProductRating::where('product_id', $product->id)->with('user')->get();
        $avg_rating = $ratings->count() ? round($ratings->sum('rating') / $ratings->count()) : null;

        // related products
        $product_category = $product?->category?->id;
        $product_id = $product->id;

        $related_products = Product::with('category','subCategory','childCategory','vendor','campaign_product','campaign_sold_product','reviews','inventory','badge','uom')
            ->withAvg("ratings", "rating")
            ->where('status_id', 1)
            ->whereIn('id', function ($query) use ($product_id, $product_category) {
                $query->select('product_categories.product_id')
                    ->from(with(new ProductCategory())->getTable())
                    ->where('product_id', '!=', $product_id)
                    ->where('category_id', '=', $product_category)
                    ->get();
            })
            ->inRandomOrder()
            ->take(5)
            ->get();

        // (bool) Check logged-in user bought this item (needed for review)
        $user = auth('sanctum')->user();

        $user_has_item = $user
            ? SubOrderItem::where("product_id", $id)->whereHas("order", function ($query){
                $query->where("user_id", auth('sanctum')->user()->id);
            })->count()
            : null;

        $user_rated_already = (bool) ProductRating::where('product_id', optional($product)->id)
            ->where('user_id', optional($user)->id)->count();

        $setting_text = StaticOption::whereIn('option_name', [
            'product_in_stock_text',
            'product_out_of_stock_text',
            'details_tab_text',
            'additional_information_text',
            'reviews_text',
            'your_reviews_text',
            'write_your_feedback_text',
            'post_your_feedback_text',
        ])->get()->mapWithKeys(fn ($item) => [$item->option_name => $item->option_value])->toArray();

        // hare will prepare vendor product as like mobileFeaturedProduct or product list
        $vendorProduct = MobileFeatureProductResource::collection($product?->vendor?->product ?? []);
        if(!empty($product?->vendor?->product)){
            unset($product->vendor->product);
            $product->vendor->product = MobileFeatureProductResource::collection($vendorProduct);
        }

        // sidebar data
        $maximum_available_price = Product::query()->with('category')->max('price');

        unset($product->cost);

        return [
            'product' => $product,
            'product_url' => route("frontend.products.single", $product->slug),
            'related_products' => MobileFeatureProductResource::collection($related_products),
            'user_has_item' => $user_has_item,
            'ratings' => $ratings,
            'avg_rating' => $avg_rating,
            'available_attributes' => $available_attributes,
            'product_inventory_set' => $product_inventory_set,
            'additional_info_store' => $additional_info_store,
            'maximum_available_price' => $maximum_available_price,
            'productColors' => $productColors,
            'productSizes' => $productSizes,
            'setting_text' => $setting_text,
            'user_rated_already' => $user_rated_already,
        ];
    }
    
    public function priceRange(){
        $max_price = Product::query()->with('category')->max('price');
        $min_price = Product::query()->min('price');
        
        return response()->json(["min_price" => $min_price, "max_price" => $max_price]);
    }

    public function storeReview(Request $request){
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['msg' => 'Login to submit rating'])->setStatusCode(422);
        }

        $request->validate([
            'id' => 'required|exists:products',
            'rating' => 'required|integer',
            'comment' => 'required|string',
        ]);

        if ($request->rating > 5) {
            $rating = 5;
        }

        // ensure rating not inserted before
        $user_rated_already = !! ProductRating::where('product_id', $request->id)->where('user_id', $user->id)->count();
        if ($user_rated_already) {
            return response()->json(['msg' => __('You have rated before')])->setStatusCode(422);
        }

        $rating = ProductRating::create([
            'product_id' => $request->id,
            'user_id' => $user->id,
            'status' => 1,
            'rating' => $request->rating,
            'review_msg' => $request->comment
        ]);

        return response()->json(["success" => true,"type" => "success","data" => $rating]);
    }

    public function searchItems(){
        return FrontendProductServices::shopPageSearchContent();
    }
}
