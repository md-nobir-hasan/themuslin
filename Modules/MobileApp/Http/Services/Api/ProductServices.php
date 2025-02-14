<?php

namespace Modules\MobileApp\Http\Services\Api;

use App\Product\Product;
use function Termwind\render;

class ProductServices
{
    // this function will return all data with response type
    // this function will receive
    public static function product_detail($id): Product|null
    {
        return Product::with("category","rating")
            ->withAvg('rating', 'rating')
            ->where("id",$id)
            ->first();
    }

    public static function get_all_product($type){
        $product_type = self::check_type($type);
        $product = Product::query()->select(["title","id","image","sale_price","price","badge","sold_count","attributes"])
            ->with(["inventory","rating","sold","campaignProduct"])
            ->withAvg('rating', 'rating');

        if($product_type == 3){
            // get maximum sold product
            $product->withMax("inventory","sold_count");
            // now set a condition to get maximum sold product
            // now set order to de-sending
            $product->orderByDesc("inventory_max_sold_count");
        }else if($product_type == 2){
            // now set a condition to get maximum sold product
            // now set order to de-sending
            $product->orderByDesc("id");
        }else if($product_type == 1){
            $product->orderBy('rating_avg_rating', 'DESC');
        }

        return self::return_item($product);
    }

    public static function search_product(){
        $product = Product::query()->with("inventory","rating","sold","campaignProduct");

        // Women's
    }

    private static function return_item($instance){
        if(request()->limit){
            $instance->take(request()->limit);
        }

        return $instance->get();
    }

    private static function check_type($type): int
    {
        switch($type){
            case "top_ratted":
                return 1;
                break;
            case "new_product":
                return 2;
                break;
            case "best_sold":
                return 3;
                break;
            default:
                return 0;
        }
    }

    public static function prepare_attributes($attributes): array
    {
        $new_attributes = [];
        foreach($attributes as $item){
            $temp_item = [];
            foreach($item as $value){

                if(array_key_exists("attribute_image",$value)){
                    $img = render_image($value->image,render_type: "path");
                    $value["attribute_image"] = !empty($img) ? $img : null;
                }

                $temp_item[] = $value;
            }

            $new_attributes[$item[0]["type"]] = $temp_item;
        }

        return $new_attributes;
    }

    public static function prepareCategory($category){
        return self::image_id_to_url($category);
    }

    public static function prepareSubCategory($sub_category): array
    {
        $temp_array = [];
        foreach($sub_category as $item){
            $item = $item->toArray();
            $temp_array[] = self::image_id_to_url($item);
        }

        return $temp_array;
    }

    private static function image_id_to_url($array,$img_key = "image"){
        if(!empty($array)){
            if(array_key_exists($img_key,$array)){
                $img = get_attachment_image_by_id($array[$img_key]);
                $array[$img_key] = $img ? $img["img_url"] : null;
            }
        }

        return $array;
    }
}