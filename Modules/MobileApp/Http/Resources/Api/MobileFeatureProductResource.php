<?php

namespace Modules\MobileApp\Http\Resources\Api;

use Exception;
use Modules\MobileApp\Http\Services\Api\ProductServices;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Entities\ProductInventoryDetail;

class MobileFeatureProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function toArray($request): array
    {
        // campaign data check
        $campaign_product = !is_null($this->campaign_product) ? $this->campaign_product : getCampaignProductById($this->id);
        $sale_price = $campaign_product ? optional($campaign_product)->campaign_price : $this->sale_price;
        $deleted_price = !is_null($campaign_product) ? $this->sale_price : $this->price;

        $campaign_percentage = !is_null($campaign_product) ? getPercentage($this->sale_price, $sale_price) : false;
        $add_to_cart = ProductInventoryDetail::where("product_id",$this->id)->where("stock_count", ">",0)->count();

        $end_date_of_campaign = !empty($campaign_product) ? ["end_date" => $campaign_product?->end_date] : [];

        return [
            "prd_id" => $this->id,
            "title" => html_entity_decode(htmlspecialchars_decode($this->name)),
            "img_url" => render_image($this->image, render_type: 'path') ?? null,
            "campaign_percentage" => round($campaign_percentage,0),
            "price" => calculatePrice(round($deleted_price,0), $this),
            "discount_price" => calculatePrice($sale_price, $this),
            "badge" => ["badge_name" => $this->badge?->name ?? null,"image" => render_image($this->badge_image, render_type: "path")],
            "campaign_product" => !empty($campaign_product),
            "campaign_stock" => !empty($campaign_product) ? $campaign_product->units_for_sale - $campaign_product->soldProduct?->sold_count : 0,
            "stock_count" => optional($this->inventory)->stock_count,
            "avg_ratting" => $this->ratings_avg_rating,
            "is_cart_able" => !$add_to_cart,
            "vendor_id" => $this->vendor_id ?? null,
            "vendor_name" => $this->vendor?->business_name,
            "category_id" => $this->category?->id,
            "sub_category_id" => $this->subCategory?->id,
            "child_category_ids" => $this->childCategory?->pluck("id")?->toArray(),
            "url" => route("frontend.products.single", $this->slug),
            "random_key" => random_int(11111111,99999999) . ($this->tax_options_sum_rate ?? 0) . random_int(111111111111111,999999999999999),
            "random_secret" => random_int(111111111111111,999999999999999) . round($sale_price,0) . random_int(11111111,99999999)
        ] + $end_date_of_campaign;
    }
}