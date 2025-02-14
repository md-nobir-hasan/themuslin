<?php

namespace App\Http\Services;

use App\AdminShopManage;

class CustomPaginationService
{
    /**
     * @param $all_products
     * @param $count
     * @param string $type
     * @param $route
     * @return mixed
     */
    public static function pagination_type($all_products, $count, string|bool $type = "custom", $route=null): mixed
    {
        $display_item_count = $count ?? 10;
        $all_products = $all_products->paginate($display_item_count);

        // check a pagination type if not custom then return Immediately from here
        if($type !== "custom"){
            return $all_products;
        }

        // check route is present or not
        $all_products_items = $all_products->transform(function ($item) {
            if(!empty($item->vendor_id) && get_static_option("calculate_tax_based_on") == 'vendor_shop_address') {
                $vendorAddress = $item->vendorAddress;
                $item = tax_options_sum_rate($item, $vendorAddress->country_id, $vendorAddress->state_id, $vendorAddress->city_id);
            }elseif(empty($item->vendor_id) && get_static_option("calculate_tax_based_on") == 'vendor_shop_address'){
                $vendorAddress = AdminShopManage::select("id","country_id", "state_id","city as city_id")->first();

                $item = tax_options_sum_rate($item, $vendorAddress->country_id, $vendorAddress->state_id, $vendorAddress->city_id);
            }

            return $item;
        });

        // if route variable is not empty, then set route to a pagination path
        if(!empty($route)){
            $all_products->withPath($route);
        }

        // set custom pagination code for pagination
        if($type == "custom"){
            $current_items = (($all_products->currentPage() - 1) * $display_item_count);
            return [
                "items" => $all_products_items,
                "current_page" => $all_products->currentPage(),
                "total_items" => $all_products->total(),
                "total_page" => $all_products->lastPage(),
                "next_page" => $all_products->nextPageUrl(),
                "previous_page" => $all_products->previousPageUrl(),
                "last_page" => $all_products->lastPage(),
                "per_page" => $all_products->perPage(),
                "path" => $all_products->path(),
                "current_list" => $all_products->count(),
                "from" => $all_products->count() ? $current_items + 1 : 0,
                "to" => $current_items + $all_products->count(),
                "on_first_page" => $all_products->onFirstPage(),
                "hasMorePages" => $all_products->hasMorePages(),
                "links" => $all_products->getUrlRange(1,$all_products->lastPage())
            ];
        }

        return $all_products;
    }
}