<?php

namespace Modules\Product\Http\Traits;

use App\Http\Services\CustomPaginationService;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductChildCategory;
use Modules\Product\Entities\ProductCreatedBy;
use Modules\Product\Entities\ProductDeliveryOption;
use Modules\Product\Entities\ProductGallery;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;
use Modules\Product\Entities\ProductInventoryDetailAttribute;
use Modules\Product\Entities\ProductSubCategory;
use Modules\Product\Entities\ProductTag;
use Modules\Product\Entities\ProductUom;
use Str;

trait ProductGlobalTrait
{
    private function product_type(): int
    {
        return 1;
    }

    public function ProductData($data): array
    {
        $user = $this->user_info();

        return [
            "name" => $data["name"],
            "slug" => Str::slug($data["slug"] ?? $data["name"]),
            "summary" => $data["summery"],
            "description" => $data["description"],
            "image_id" => $data["image_id"],
            "price" => $data["price"] ?? null,
            "sale_price" => $data["sale_price"],
            "cost" => $data["cost"] ?? null,
            "badge_id" => $data["badge_id"],
            "brand_id" => !empty($data["brand"]) ? $data["brand"] : 0,
            "status_id" => $data["status_id"] ?? 1,
            "product_type" => $this->product_type() ?? 2,
            "min_purchase" => $data["min_purchase"],
            "max_purchase" => $data["max_purchase"],
            "is_inventory_warn_able" => $data["is_inventory_warn_able"] ? 1 : 2,
            "is_refundable" => !empty($data["is_refundable"]),
            "is_in_house" => $user["type"] == "admin" ? 1 : 0,
            "admin_id" => $user["type"] == "admin" ? $user["id"] : null,
            "vendor_id" => $user["type"] == "vendor" ? $user["id"] : null,
            "is_taxable" => $data["is_taxable"] ?? 0,
            "tax_class_id" => ($data["is_taxable"] ?? 0) == 1 ? $data["tax_class_id"] ?? null : null,
            "show_home" => !empty($data["show_home"]) ? $data["show_home"] : 0,
            "sort_order" => $data["sort_order"],
            "height" => $data["height"],
            "width"  => $data["width"],
            "weight" => $data["weight"],
            "length" => $data["length"],
            "cost_usd" => $data["cost_usd"],
            "price_usd" => $data["price_usd"],
            "sale_price_usd" => $data["sale_price_usd"],
            "increase_percentage_usd" => $data["increase_percentage_usd"],



        ];
    }

    public function prepareProductInventoryDetailsAndInsert($data, $product_id, $inventory_id, $type = "create"): bool
    {
        // Retrieve existing inventory details IDs for the product and inventory
        $existingInventoryIds = ProductInventoryDetail::where([
            'product_id' => $product_id,
            'product_inventory_id' => $inventory_id
        ])->pluck('id')->toArray();

        // Get the IDs from the $data array (the form submission data)
        $submittedInventoryIds = $data['inventory_details_id'] ?? [];

        // Find the IDs that exist in the database but not in the submitted data (missing IDs)
        $missingIds = array_diff($existingInventoryIds, $submittedInventoryIds);

        // Delete the missing IDs from the ProductInventoryDetail table
        if (!empty($missingIds)) {
            ProductInventoryDetail::whereIn('id', $missingIds)->delete();
        }


        // count item stock for getting array length
        $len = count($data["item_stock_count"] ?? []);

        for ($i = 0; $i < $len; $i++) {

            $arr = [
                "product_id" => $product_id,
                "product_inventory_id" => $inventory_id,
                "color" => $data["item_color"][$i],
                "size" => $data["item_size"][$i],
                "hash" => "",
                "additional_price" => $data["item_additional_price"][$i],
                "add_cost" => $data["item_extra_cost"][$i],
                // "image" => $data["item_image"][$i],
                "image" => null,
                "stock_count" => $data["item_stock_count"][$i],
                "sold_count" => 0,
                "height" => $data["item_height"][$i],
                "width"  => $data["item_width"][$i],
                "weight" => $data["item_weight"][$i],
                "length" => $data["item_length"][$i],
            ];


            $variation_inventory = NULL;

            if ($type == "update") {

                $variation_inventory = ProductInventoryDetail::where([
                    "product_id" => $product_id,
                    "product_inventory_id" => $inventory_id,
                    'color' => $data["item_color"][$i],
                    'size' => $data["item_size"][$i],
                ])->first();
            }

            if ($variation_inventory) {
                // Update the existing row with the new data
                $variation_inventory->update($arr);
            }
            else {

                $productDetailId = ProductInventoryDetail::create($arr);

                $detailAttr = [];
                for ($j = 0, $length = count($data["item_attribute_name"][$i] ?? []); $j < $length; $j++) {
                    $detailAttr[] = [
                        "product_id" => $product_id,
                        "inventory_details_id" => $productDetailId->id,
                        "attribute_name" => $data["item_attribute_name"][$i][$j] ?? "",
                        "attribute_value" => $data["item_attribute_value"][$i][$j] ?? ""
                    ];
                }

                ProductInventoryDetailAttribute::insert($detailAttr);
            }
            
        }

        return true;
    }

    private function productCategoryData($data, $id, $arrKey = "category_id", $column = "category_id"): array
    {
        return [
            $arrKey => $data[$column],
            "product_id" => $id
        ];
    }

    private function childCategoryData($data, $id): array
    {
        $cl_category = [];
        foreach ($data["child_category"] ?? [] as $item) {
            $cl_category[] = ["child_category_id" => $item, "product_id" => $id];
        }

        return $cl_category;
    }

    private function prepareDeliveryOptionData($data, $id): array
    {
        // explode string to array
        $arr = [];
        $exp_delivery_option = $this->separateStringToArray($data["delivery_option"], " , ") ?? [];

        foreach ($exp_delivery_option as $option) {
            $arr[] = [
                "product_id" => $id,
                "delivery_option_id" => $option
            ];
        }

        return $arr;
    }

    public function prepareProductGalleryData($data, $id): array
    {
        $galleries = $this->separateStringToArray(($data["product_gallery"] ?? '') ?? ($data->product_gallery ?? ''), "|");
        // explode string to array
        $arr = [];

        foreach ($galleries as $image) {
            $arr[] = [
                "product_id" => $id,
                "image_id" => $image
            ];
        }

        return $arr;
    }

    private function prepareProductTagData($data, $id): array
    {
        // explode string to array
        $arr = [];
        $galleries = $this->separateStringToArray($data["tags"], ",");

        foreach ($galleries as $tag) {
            $arr[] = [
                "product_id" => $id,
                "tag_name" => $tag
            ];
        }

        return $arr;
    }

    private function prepareInventoryData($data, $id = null): array
    {
        $inventoryStockCount = $data["item_stock_count"];
        $stock_count = is_array($inventoryStockCount) ? array_sum($inventoryStockCount ?? []) : false;

        $arr = [
            "sku" => $data["sku"],
            "stock_count" => $stock_count ? $stock_count : $data["quantity"],
            "unit_id" => $data["unit_id"],
            "quantity" => $data["uom"],
        ];

        return $id ? $arr + ["product_id" => $id] : $arr;
    }

    private function separateStringToArray(string|null $string, string $separator = " , "): array|bool
    {
        if (empty($string)) return [];
        return explode($separator, $string);
    }

    public function prepareMetaData($data): array
    {
        return [
            'meta_title' => $data["general_title"] ?? '',
            'meta_tags' => $data["general_title"] ?? '',
            'meta_description' => $data["general_description"] ?? '',
            'facebook_meta_tags' => $data["facebook_title"] ?? '',
            'facebook_meta_description' => $data["facebook_description"] ?? '',
            'facebook_meta_image' => $data["facebook_meta_image"] ?? '',
            'twitter_meta_tags' => $data["twitter_title"] ?? '',
            'twitter_meta_description' => $data["twitter_description"] ?? '',
            'twitter_meta_image' => $data["twitter_image"] ?? ''
        ];
    }

    private function userId()
    {
        if(\Auth::guard("admin")->check()){
            return \Auth::guard("admin")->user()->id;
        }

        if(\Auth::guard("vendor")->check()){
            return \Auth::guard("vendor")->user()->id;
        }

        if(\Auth::guard("sanctum")->check()){
            return \Auth::guard("sanctum")->user()->id;
        }

        return \Auth::guard("admin")->check() ? \Auth::guard("admin")->user()->id : \Auth::guard("vendor")->user()->id;
    }

    private function user_info(): array
    {
        return \Auth::guard("admin")->check() ?
            [
                "id" => \Auth::guard("admin")->user()->id,
                "type" => "admin",
            ] : [
                "id" => \Auth::guard("vendor")->user()->id,
                "type" => "vendor",
            ];
    }

    private function getGuardName(): string
    {
        return \Auth::guard("admin")->check() ? "admin" : "vendor";
    }


    private function createArrayCreatedBy($product_id, $type): array
    {
        $arr = [];

        if ($type == 'create') {
            $arr = [
                "product_id" => $product_id,
                "created_by_id" => $this->userId(),
                "guard_name" => $this->getGuardName(),
            ];
        } elseif ($type == 'update') {
            $arr = [
                "product_id" => $product_id,
                "updated_by" => $this->userId(),
                "updated_by_guard" => $this->getGuardName(),
            ];
        } elseif ($type == 'delete') {
            $arr = [
                "product_id" => $product_id,
                "deleted_by" => $this->userId(),
                "deleted_by_guard" => $this->getGuardName(),
            ];
        }

        return $arr;
    }

    public function createdByUpdatedBy($product_id, $type = "create"): ProductCreatedBy
    {
        return ProductCreatedBy::updateOrCreate(
            [
                "product_id" => $product_id
            ],
            $this->createArrayCreatedBy($product_id, $type)
        );
    }

    private function prepareUomData($data, $product_id): array
    {
        return [
            "product_id" => $product_id,
            "unit_id" => $data["unit_id"],
            "quantity" => $data["uom"]
        ];
    }

    public function updateStatus($productId, $statusId): JsonResponse
    {
        $product = Product::find($productId)->update(["status_id" => $statusId]);
        $this->createdByUpdatedBy($productId, "update");

        $response_status = $product ? ["success" => true,"type" => "success", "msg" => __("Successfully updated status")] : ["success" => false,"type" => "danger", "msg" => __("Failed to update status")];
        return response()->json($response_status)->setStatusCode(200);
    }

    protected function productInstance($type): Builder
    {
        $product = Product::query();
        if ($type == "edit") {
            $product->with(["product_category","uom", "gallery_images", "tag", "uom", "product_sub_category", "product_child_category", "image", "inventory", "delivery_option"]);
        } elseif ($type == "single") {
            $product->with(["category","uom", "gallery_images", "tag", "uom", "subCategory", "childCategory", "image", "inventory", "delivery_option"]);
        } elseif ($type == "list") {
            $product->with(["category", "uom", "subCategory", "childCategory", "brand", "badge", "image", "inventory"]);
        } elseif ($type == "search") {
            $product->with(["category", "uom", "subCategory", "childCategory", "brand", "badge", "image", "inventory"]);
        } else {
            $product = $product->with(["category", "subCategory", "childCategory", "brand", "badge", "image", "inventory"]);
        }

        return $product;
    }

    private function get_product($id, $type = "single"): Model|Builder|null
    {
        // get product instance
        $product = $this->productInstance($type);

        return $product->find($id);
    }

    public function productStore($data): bool
    {
        $product_data = self::ProductData($data);
        $product = Product::create($product_data);
        $id = $product->id;
        $product->metaData()->create($this->prepareMetaData($data));
        // store created by info in product created by table
        $this->createdByUpdatedBy($id);
        return $this->product_relational_data_insert($data, $id, $data);
    }

    public function productUpdate($data, $id): bool
    {   
        $product_data = self::ProductData($data);
        $product = $this->get_product($id);

        $product->update($product_data);
        ProductInventory::updateOrCreate(["product_id" => $id], $this->prepareInventoryData($data));
        // updated by info in product created by table
        $this->createdByUpdatedBy($id, "update");
        // check item stock count is empty or not
        $inventoryDetail = false;

        if (!empty($data["item_stock_count"][0])) {
            $inventoryDetail = $this->prepareProductInventoryDetailsAndInsert($data, $id, $product?->inventory?->id, "update");
        }
        ProductCategory::updateOrCreate(["product_id" => $id], $this->productCategoryData($data, $id));
        // this condition will make sub category optional

        if($data['sub_category'] ?? false)
            ProductSubCategory::updateOrCreate(["product_id" => $id], $this->productCategoryData($data, $id, "sub_category_id", "sub_category"));

        // delete product child category
        ProductChildCategory::where("product_id", $id)->delete();
        ProductDeliveryOption::where("product_id", $id)->delete();
        ProductGallery::where("product_id", $id)->delete();
        ProductTag::where("product_id", $id)->delete();

        ProductUom::updateOrCreate(["product_id" => $id],$this->prepareUomData($data, $id));
        // this condition is for making child category optional
        if($data["child_category"][0] ?? false)
            ProductChildCategory::insert($this->childCategoryData($data, $id));
        // this condition is for making delivery_option optional
        if($data["delivery_option"] ?? false)
            ProductDeliveryOption::insert($this->prepareDeliveryOptionData($data, $id));
        // this condition is for making product_gallery optional
        if(!empty(($data["product_gallery"] ?? []) ?? ($data->product_gallery ?? [])))
            ProductGallery::insert($this->prepareProductGalleryData($data, $id));
        // this condition is for making tags optional
        if($data["tags"] ?? false)
            ProductTag::insert($this->prepareProductTagData($data, $id));

        return true;
    }

    public function CloneData($data): array
    {
        $user = $this->user_info();

        return [
            "name" => $data->name,
            "slug" => createSlug(Str::slug($data->slug ?? $data->name), "product", true, "product"),
            "summary" => $data->summary,
            "description" => $data->description,
            "image_id" => $data->image_id,
            "price" => $data->price,
            "sale_price" => $data->sale_price,
            "cost" => $data->cost,
            "badge_id" => $data->badge_id,
            "brand_id" => $data->brand_id,
            "status_id" => $data->status_id ?? 2,
            "product_type" => $this->product_type() ?? 2,
            "min_purchase" => $data->min_purchase,
            "max_purchase" => $data->max_purchase,
            "is_inventory_warn_able" => $data->is_inventory_warn_able ? 1 : 2,
            "is_refundable" => !empty($data->is_refundable),
            "is_in_house" => $user["type"] == "admin" ? 1 : 0,
            "admin_id" => $user["type"] == "admin" ? $user["id"] : null,
            "vendor_id" => $user["type"] == "vendor" ? $user["id"] : null,
        ];
    }

    public function productClone($id): bool
    {
        $data = array();
        $product = Product::findOrFail($id);
        $product_data = self::CloneData($product);

        $newProduct = $product->create($product_data);
        $id = $newProduct->id;
        $metaData = [];

        if ($product?->metaData) {
            $metaData = [
               // 'general_title' => $product?->metaData?->meta_tags,
                'general_title' => $product?->metaData?->meta_title,
                'general_description' => $product?->metaData?->meta_description,
                'facebook_title' => $product?->metaData?->facebook_meta_tags,
                'facebook_description' => $product?->metaData?->facebook_meta_description,
                'facebook_meta_image' => $product?->metaData?->facebook_meta_image,
                'twitter_title' => $product?->metaData?->twitter_meta_tags,
                'twitter_description' => $product?->metaData?->twitter_meta_description,
                'twitter_image' => $product?->metaData?->twitter_meta_image,
            ];
        }

        $newProduct->metaData()->create($this->prepareMetaData($metaData));

        $this->createdByUpdatedBy($id);

        $data["sku"] = createSlug(optional($product->inventory)->sku, 'ProductInventory', true, 'Product', 'sku');
        $inventoryQuantity = $product?->inventory?->stock_count;

        $product->category_id = optional($product->category)->id;
        $product->sub_category = optional($product->subCategory)->id;
        $product->child_category = current(optional($product->childCategory)->pluck('id'));

        $delivery_option = current(optional($product->delivery_option)->pluck('delivery_option_id'));
        $product->delivery_option = implode(' , ', $delivery_option);

        $product_gallery = current(optional($product->product_gallery)->pluck('image_id'));
        $product->product_gallery = implode('|', $product_gallery);

        $product_tags = current(optional($product->tag)->pluck('tag_name'));
        $product->tags = implode(',', $product_tags);

        $data["unit_id"] = $product->uom?->unit_id;
        $data["uom"] = $product->uom?->quantity;

        // product attributes
        $data['item_stock_count'] = count(optional($product->inventory)->inventoryDetails);
        $product->item_stock_count = \Arr::wrap($data['item_stock_count']);
        $quantity = $product?->inventory?->stock_count ?? 0;

        if ($data['item_stock_count'] > 0) {
            $data['item_stock_count'] = array();
            foreach ($product->inventory?->inventoryDetails ?? [] as $i => $details) {
                $data['item_color'][$i] = $details->color;
                $data['item_size'][$i] = $details->size;
                $data['item_additional_price'][$i] = $details->additional_price;
                $data['item_extra_cost'][$i] = $details->add_cost;
                $data['item_image'][$i] = $details->image;
                $data['item_stock_count'][$i] = $details->stock_count;

                foreach ($details->attribute ?? [] as $j => $attribute) {
                    $data['item_attribute_name'][$i][$j] = $attribute->attribute_name;
                    $data['item_attribute_value'][$i][$j] = $attribute->attribute_value;
                }
            }
        }

        $data["quantity"] = $quantity ?? $inventoryQuantity;

        return $this->product_relational_data_insert($data, $id, $product);
    }

    public function updateInventory($data, $id): bool
    {
        $product = Product::find($id);
        $updateData = $this->prepareInventoryData($data);

        $product->inventory->updateOrCreate(["product_id" => $id], $updateData);
        $product->uom?->updateOrCreate(["product_id" => $id], $updateData);

        // updated by info in product created by table
        $this->createdByUpdatedBy($id, "update");
        // check item stock count is empty or not
        $inventoryDetail = false;
        if (count($data["item_stock_count"] ?? [])) {
            $inventoryDetail = $this->prepareProductInventoryDetailsAndInsert($data, $id, $product?->inventory?->id, "update");
        }

        return true;
    }

    protected function destroy($id): ?bool
    {
        return Product::find($id)?->delete() ?? false;
    }

    protected function trash_destroy($id): bool
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        ProductUom::where('product_id', $product->id)->delete();
        ProductTag::where('product_id', $product->id)->delete();
        ProductGallery::where('product_id', $product->id)->delete();
        ProductDeliveryOption::where('product_id', $product->id)->delete();
        ProductChildCategory::where('product_id', $product->id)->delete();
        ProductSubCategory::where('product_id', $product->id)->delete();
        ProductCategory::where('product_id', $product->id)->delete();
        ProductInventoryDetailAttribute::where('product_id', $product->id)->delete();
        ProductInventoryDetail::where('product_id', $product->id)->delete();
        ProductInventory::where('product_id', $product->id)->delete();
        $product->forceDelete();

        return (bool)$product;
    }

    protected function bulk_delete($ids)
    {
        $product = Product::whereIn('id', $ids)->delete();
        return (bool)$product;
    }

    protected function trash_bulk_delete($ids): bool
    {
        try {
            ProductUom::whereIn('product_id', $ids)->delete();
            ProductTag::whereIn('product_id', $ids)->delete();
            ProductGallery::whereIn('product_id', $ids)->delete();
            ProductDeliveryOption::whereIn('product_id', $ids)->delete();
            ProductChildCategory::whereIn('product_id', $ids)->delete();
            ProductSubCategory::whereIn('product_id', $ids)->delete();
            ProductCategory::whereIn('product_id', $ids)->delete();
            ProductInventoryDetailAttribute::whereIn('product_id', $ids)->delete();
            ProductInventoryDetail::whereIn('product_id', $ids)->delete();
            ProductInventory::whereIn('product_id', $ids)->delete();

            $products = Product::onlyTrashed()->whereIn('id', $ids)->forceDelete();
        } catch (Exception $exception) {
            return false;
        }

        return (bool)$products;
    }

    /**
     * @throws Exception
     */
    private function search($request, $route = 'admin',$queryType = "admin", $isCustomPagination = "custom")
    {
        $type = $request->type ?? 'default';
        $multiple_date = $this->is_date_range_multiple();

        $all_products = null;

        // create product model instance
        if($queryType == 'admin'){
            $all_products = Product::query()->with("brand", "category", "childCategory", "subCategory", "inventory","vendor",)
                ->where("vendor_id", null);
        }else if ($queryType == 'frontend'){

            $all_products = Product::query()
                ->with([
                    // 'campaign_sold_product',
                    'category', 
                    'subCategory',
                    'childCategory',
                    // 'campaign_product' => function ($query){
                    //     $query = productCampaignConditionWith($query);
                    // },
                    // 'inventory',
                    // 'badge',
                    // 'uom',
                    // 'vendor',
                    // "taxOptions:tax_class_options.id,country_id,state_id,city_id,rate",
                    // "vendorAddress:vendor_addresses.id,country_id,state_id,city_id"
                ])
                // ->withAvg("ratings", "rating")
                // ->withCount("ratings")
                ->where("status_id", 1);
                // ->withSum("taxOptions", "rate");
            // call a function for campaign this function will add condition to this table
        }else if ($queryType == 'api'){
            $all_products = Product::query()
                ->with([
                    'campaign_sold_product',
                    'category',
                    'subCategory',
                    'childCategory',
                    'campaign_product' => function ($query){
                        // call a function for campaign this function will add condition to this table
                        $query = productCampaignConditionWith($query);
                    },
                    'inventoryDetail' => function ($query){
                        $query->where("stock_count",">", 0);
                    },
                    'inventory',
                    'badge',
                    'uom',
                    'vendor',
                    "taxOptions:tax_class_options.id,country_id,state_id,city_id,rate",
                    "vendorAddress:vendor_addresses.id,country_id,state_id,city_id"
                ])->withAvg("ratings", "rating")
                ->withCount("ratings")
                ->withSum("taxOptions", "rate")
                ->whereHas("status", function ($query){
                    $query->where("name", "Active");
                });
        }else if ($queryType == 'vendor'){
            $all_products = Product::query()
                ->select("id","name","status_id","image_id","brand_id")
                ->with("brand:id,name","inventory:id,stock_count,product_id","status:id,name","category","subCategory","childCategory","brand")->without("badge","uom");
        }

        // first, I need to check who is currently want to take data
        // run a condition that will check if vendor is currently login then only vendor product will return

        // search product name
        $all_products->when(\Auth::guard("vendor")->check(), function ($query) use ($request) {
            $query->where("vendor_id", \Auth::guard("vendor")->id());
        })->when(!\Auth::guard("vendor")->check() && $request->vendor_username, function ($query) use ($request) {
            $query->whereHas("vendor", function ($vendor) use ($request) {
                $vendor->where("username", $request->vendor_username);
            });
        })->when(auth("sanctum")->check() && $queryType == 'vendor', function ($query) use ($request) {
            $query->whereHas("vendor", function ($vendor) use ($request) {
                $vendor->where("id", auth("sanctum")->id());
            });
        })->when(!empty($request->name) && $request->has("name"), function ($query) use ($request) {
            $query->where("name", "LIKE", "%" . trim(strip_tags($request->name)) . "%");
        })->when(!empty($request->tag) && $request->has("tag"), function ($query) use ($request) {// search by using tag
            $query->whereHas("tag", function ($i_query) use ($request) {
                $i_query->where("tag_name", "like", "%" . $request->tag . "%");
            });
        })->when(!empty($request->category) && $request->has("category"), function ($query) use ($request) { // category
            $query->whereHas("category", function ($i_query) use ($request) {

                foreach ($request->category as $r_category) {
                    $i_query->where("slug", "like", "%" . trim(strip_tags($r_category)) . "%");
                }

                // $i_query->where("name", "like", "%" . trim(strip_tags($request->category)) . "%");
            });
        })->when(!empty($request->sub_category) && $request->has("sub_category"), function ($query) use ($request) { // sub category
            $query->whereHas("subCategory", function ($i_query) use ($request) {

                foreach ($request->sub_category as $r_subcategory) {
                    $i_query->where("slug", "like", "%" . trim(strip_tags($r_subcategory)) . "%");
                }

                // $i_query->where("name", "like", "%" . trim(strip_tags($request->sub_category)) . "%");
            });
        })->when(!empty($request->child_category) && $request->has("child_category"), function ($query) use ($request) {
            $query->whereHas("childCategory", function ($i_query) use ($request) {
                $i_query->where(function ($q) use ($request) {
                    foreach ($request->child_category as $r_childcategory) {
                        $q->orWhere("slug", "like", "%" . trim(strip_tags($r_childcategory)) . "%");
                    }
                });
            });
            // $i_query->where("name", "like", "%" . trim(strip_tags($request->child_category)) . "%");

        })->when(!empty($request->category_id) && $request->has("category_id"), function ($query) use ($request) { // category
            $query->whereHas("category", function ($i_query) use ($request) {
                $i_query->where("categories.id",trim(strip_tags($request->category_id)));
            });
        })->when(!empty($request->sub_category_id) && $request->has("sub_category_id"), function ($query) use ($request) { // sub category
            $query->whereHas("subCategory", function ($i_query) use ($request) {
                $i_query->where("sub_categories.id",trim(strip_tags($request->sub_category_id)));
            });
        })->when(!empty($request->child_category_id) && $request->has("child_category_id"), function ($query) use ($request) { // child category
            $query->whereHas("childCategory", function ($i_query) use ($request) {
                $i_query->where("child_categories.id",trim(strip_tags($request->child_category_id)));
            });
        })->when(!empty($request->brand) && $request->has("brand"), function ($query) use ($request) { // Brand
            $query->whereHas("brand", function ($i_query) use ($request) {
                $i_query->where("name", "like", "%" . trim(strip_tags($request->brand)) . "%");
            });

        })->when(!empty($request->color) && $request->has("color"), function ($query) use ($request) {
            $query->whereHas("color", function ($i_query) use ($request) {
                $i_query->where(function ($q) use ($request) {
                    foreach ($request->color as $r_color) {
                        $q->orWhere("colors.name", "like", "%" . trim(strip_tags($r_color)) . "%");
                    }
                });
            });

        })->when(!empty($request->size) && $request->has("size"), function ($query) use ($request) {
            $query->whereHas("size", function ($i_query) use ($request) {
                $i_query->where(function ($q) use ($request) {
                    foreach ($request->size as $r_color) {
                        $q->orWhere("sizes.name", "like", "%" . trim(strip_tags($r_color)) . "%");
                    }
                });
            });
        })->when(!empty($request->sku) && $request->has("sku"), function ($query) use ($request) { // sku
            $query->whereHas("inventory", function ($i_query) use ($request) {
                $i_query->where("sku", "like", trim(strip_tags($request->sku) . "%"));
            });
        })->when(!empty($request->delivery_option) && $request->has("delivery_option"), function ($query) use ($request) { // delivery option
            $query->whereHas("productDeliveryOption", function ($i_query) use ($request) {
                $i_query->where("title", "like", "%" . trim(strip_tags($request->delivery_option)) . "%");
            });
        })->when(!empty($request->refundable) && $request->has("refundable"), function ($query) use ($request) { // refundable
            $query->where("is_refundable", 1);
        })->when(!empty($request->inventory_warning) && $request->has("inventory_warning"), function ($query) use ($request) { // inventory warning
            $query->where("is_inventory_warn_able", 1);
        })->when(!empty($request->from_price) && $request->has("from_price") && !empty($request->to_price) && $request->has("to_price"), function ($query) use ($request) { // price
            $query->whereBetween("sale_price", [$request->from_price, $request->to_price]);
        })->when($multiple_date[0] && $request->has("date_range"), function ($query) use ($request, $multiple_date) { // Order By
            // make separate to date in an array
            $arr = $multiple_date[1];

            $query->whereBetween("created_at", [$arr[0], $arr[1]]);
        })->when(!empty($request->min_price ?? null) && !empty($request->max_price ?? ""), function ($query) use ($request) { // Order By
            // now makes whereBetween condition for search product
            $query->whereBetween("sale_price", [$request->min_price, $request->max_price]);
        })->when(!empty($request->order_by) && $request->has("order_by"), function ($query) use ($request) { // Order By
            if ($request->order_by === 'low_high') {
                $query->orderBy('sale_price', 'asc'); // Low to high
            } elseif ($request->order_by === 'high_low') {
                $query->orderBy('sale_price', 'desc'); // High to low
            } else {
                $query->orderBy("name", $request->order_by);
            }
        })->when(!empty($request->rating), function ($query) use ($request) {
            $query->having('ratings_avg_rating', '>=', $request->rating);
            $query->having('ratings_avg_rating', '>=', ($request->rating - 1));
        }); 

        $display_item_count = request()->count ?? 30;
        $current_query = request()->all();
        $create_query = http_build_query($current_query);

        return CustomPaginationService::pagination_type($all_products, $display_item_count, $isCustomPagination, route($route . ".products.search") . '?' . $create_query);
    }

    /**
     * @throws Exception
     */
    private function is_date_range_multiple(): array
    {
        $date = explode(" to ", request()->date_range);

        if(count($date) > 1 && !empty(request()->date_range)){
            foreach($date as $key => $value){
                $date[$key] = new DateTime($value);
            }

            return [true , $date];
        }

        return [false, request()->date_range];
    }

    /**
     * @param array $data
     * @param $id
     * @param $product
     * @return bool
     */
    private function product_relational_data_insert(array $data, $id, $product): bool
    {
        $inventory = ProductInventory::create($this->prepareInventoryData($data, $id));
        $inventoryDetail = false;

        if (!empty($product["item_stock_count"][0])) {
            $this->prepareProductInventoryDetailsAndInsert($data, $id, $inventory->id);
        }

        ProductCategory::create($this->productCategoryData($product, $id));
        // this condition will make sub category optional
        if($product['sub_category'] ?? false)
            ProductSubCategory::create($this->productCategoryData($product, $id, "sub_category_id", "sub_category"));
        // this condition is for making child category optional
        if($data["child_category"][0] ?? false)
            ProductChildCategory::insert($this->childCategoryData($product, $id));
        // this condition is for making delivery_option optional
        if($product["delivery_option"] ?? false)
            ProductDeliveryOption::insert($this->prepareDeliveryOptionData($product, $id));
        // this condition is for making product_gallery optional
        if(!empty(($product["product_gallery"] ?? []) ?? ($product->product_gallery ?? [])))
            ProductGallery::insert($this->prepareProductGalleryData($product, $id));
        // this condition is for making tags optional
        if($product["tags"] ?? false)
            ProductTag::insert($this->prepareProductTagData($product, $id));

        ProductUom::create($this->prepareUomData($data, $id));

        return true;
    }

    public static function fetch_inventory_product()
    {
        return ProductInventory::query()->with("product")
            ->when(\Auth::guard("vendor")->check(), function ($query){
                $query->whereHas("product", function ($ven_query){
                    $ven_query->where("vendor_id", \Auth::guard("vendor")->id());
                });
            });
    }
}