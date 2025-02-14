<?php

namespace Modules\Campaign\Http\Controllers;

use App\AdminShopManage;
use App\Http\Services\CustomPaginationService;
use Modules\Campaign\Entities\Campaign;
use Modules\Product\Entities\Product;

class FrontendCampaignController
{
    public function campaignPage($slug){
        $campaign = Campaign::with("campaignImage")
            ->whereHas("product")
            ->where("slug", $slug)->firstOrFail();
        $products = Product::withCount("orderItems","inventoryDetail","ratings")
            ->with("campaign_product","campaign_sold_product","inventory","taxOptions:tax_class_options.id,country_id,state_id,city_id,rate","vendorAddress:vendor_addresses.id,country_id,state_id,city_id")
            ->withAvg("ratings",'rating')
            ->orderByDesc("order_items_count")
            ->whereHas("campaign_product", function ($campaignProduct) use($campaign){
                $campaignProduct->where("campaign_id", $campaign->id)->where("end_date", ">", now());
            });

        $products = CustomPaginationService::pagination_type($products, 10);

        return view("frontend.campaign.index", compact("campaign","products"));
    }
}