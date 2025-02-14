<?php

namespace Modules\CountryManage\Http\Controllers\Campaign;

use Modules\Campaign\Entities\CampaignSoldProduct;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class CampaignSoldProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 
        // Remove from Inventory
        // Add in CampaignSoldProduct
        //      - product_id
        //      - sold_count
        //      - total_amount : $CampaignProduct->campaign_price * sold_count
    }

    /**
     * Display the specified resource.
     *
     * @param  \Modules\Campaign\Entities\CampaignSoldProduct  $campaignSoldProduct
     * @return \Illuminate\Http\Response
     */
    public function show(CampaignSoldProduct $campaignSoldProduct)
    {
        // 
        //  @input CampaignProduct->id [or] Product->id  --- [?]
        // Show Detail of a CampaignProduct's sell details
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Modules\Campaign\Entities\CampaignSoldProduct  $campaignSoldProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CampaignSoldProduct $campaignSoldProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modules\Campaign\Entities\CampaignSoldProduct  $campaignSoldProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(CampaignSoldProduct $campaignSoldProduct)
    {
        //  delete a particular sell
    }
}
