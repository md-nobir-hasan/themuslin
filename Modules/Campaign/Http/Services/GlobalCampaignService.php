<?php

namespace Modules\Campaign\Http\Services;

use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Modules\Campaign\Entities\Campaign;
use Modules\Campaign\Entities\CampaignProduct;
use Modules\Product\Entities\Product;

class GlobalCampaignService
{
    private function campaignInstance(): Builder|Campaign
    {
        return Campaign::query()->with("campaignImage");
    }

    private function campaign_for()
    {
        return $this->campaignInstance()->when(\Auth::guard("vendor")->check(), function ($query){
            $query->where("vendor_id", \Auth::guard("vendor")->user()->id)->where("type", "vendor");
        })->when(\Auth::guard("admin")->check(), function ($query){
            $query->where("vendor_id", null)->where("type", "admin");
        });
    }

    public static function fetch_campaigns($id = null,$isApiVendor = false)
    {
        $campaign = (new self)->campaign_for();
        if($id != null){
            return $campaign->where("id", $id)->first();
        }

        if($isApiVendor){
            $campaign->where("vendor_id", auth("sanctum")->id());
        }

        return $campaign->get();
    }

    /**====================================================================
     *                  CAMPAIGN PRODUCT FUNCTIONS
    ==================================================================== */
    public static function updateCampaignProducts($campaign_id, $data)
    {
        try {
            DB::beginTransaction();
            (new self)->deleteCampaignProducts($data['products']['product_id']);
            self::insertCampaignProducts($campaign_id, $data["products"], $data['start_date'], $data['end_date']);

            DB::commit();
        }catch(\Throwable $th) {
            DB::rollBack();

            return false;
        }
    }

    public static function insertCampaignProducts($campaign_id, $products_data, $start_date = null, $end_date = null): bool
    {
        $insert_data = [];

        foreach ($products_data['product_id'] as $key => $value) {
            $insert_data[$products_data['product_id'][$key]] = [
                'campaign_id' => $campaign_id,
                'product_id' => $products_data['product_id'][$key],
                'campaign_price' => $products_data['campaign_price'][$key],
                'units_for_sale' => $products_data['units_for_sale'][$key],
                'start_date' => $products_data['start_date'][$key] ?? $start_date,
                'end_date' => $products_data['end_date'][$key] ?? $end_date,
            ];
        }

        return (bool) CampaignProduct::insert($insert_data);
    }

    public function deleteCampaignProducts($all_product_id): bool
    {
        return (bool) CampaignProduct::whereIn('product_id', $all_product_id)->delete();
    }

    public static function renderCampaignProduct($url,$id = null): Factory|View|Application
    {
        $other_campaign_products = CampaignProduct::query()->select('product_id');
        $campaign = null;

        $view = 'new';
        if($id) :
            $campaign = Campaign::with(['products', 'products.product', 'products.product.inventory', 'products','admin','vendor','campaignImage'])->findOrFail($id);
            $other_campaign_products = $other_campaign_products->where('campaign_id', '!=', $campaign->id);
            $view = 'edit';
        endif;

        $other_campaign_products = $other_campaign_products->pluck('product_id')->toArray();
        $all_products = Product::with('inventory')->where('status_id', 1)->whereNotIn('id', $other_campaign_products)->get();

        return view($url . $view, compact('campaign', 'all_products'));
    }

    public static function campaignDetails($id){
       return Campaign::with('campaignImage','product.category','product.subCategory','product.childCategory','product', 'product.inventory','product.brand','product.vendor')
            ->where("end_date", ">", Carbon::now())
            ->where("id", $id)
            ->first();
    }
}