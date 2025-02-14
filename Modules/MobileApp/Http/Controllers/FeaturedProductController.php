<?php

namespace Modules\MobileApp\Http\Controllers;

use Modules\Campaign\Entities\Campaign;
use Modules\Campaign\Entities\CampaignProduct;
use Illuminate\Routing\Controller;
use Modules\MobileApp\Http\Resources\Api\MobileFeatureProductResource;
use Modules\MobileApp\Http\Services\Api\MobileFeaturedProductService;
use Modules\MobileApp\Entities\MobileCampaign;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Product\Entities\Product;

class FeaturedProductController extends Controller
{
    public function index(){
        $product = MobileFeaturedProductService::get_product();

        return MobileFeatureProductResource::collection($product);
    }

    #[ArrayShape(["products" => "array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable", "campaign_info" => "array"])]
    public function campaign($id = null){
        // fetch all product id from a selected campaign
        if(!empty($id)){
            $campaignId = $id;
        }else{
            $mobileCampaign = MobileCampaign::first();
            $campaignId = $mobileCampaign->campaign_id;
        }

        $campaign = Campaign::where("id" , $campaignId)->first();
        $selectedCampaignProductId = CampaignProduct::select("product_id")
            ->where("campaign_id", $campaignId)->get()->pluck("product_id")->toArray();
        // get all products from this campaign
        $products = addonProductInstance()
            ->whereIn('id',$selectedCampaignProductId)->paginate(20);

        $products = MobileFeatureProductResource::collection($products)->response()->getData();

        return ["products" => $products ,"campaign_info" => optional($campaign)->toArray()];
    }

    public function homepageCamapaign(){
        $campaignId = MobileCampaign::first();

        return $campaignId;
    }
}
