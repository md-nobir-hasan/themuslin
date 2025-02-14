<?php

namespace Modules\Campaign\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Campaign\Entities\Campaign;
use Modules\Campaign\Http\Resources\VendorCampaignDetailsResource;
use Modules\Campaign\Http\Resources\VendorCampaignListResource;
use Modules\Campaign\Http\Services\GlobalCampaignService;

class VendorCampaignApiController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $all_campaigns =  GlobalCampaignService::fetch_campaigns(isApiVendor: 'vendor');

        return VendorCampaignListResource::collection($all_campaigns);
    }

    public function details($id)
    {
        $campaign = GlobalCampaignService::campaignDetails($id);

        return new VendorCampaignDetailsResource($campaign);
    }

    public function updateStatus(Request $request)
    {
        // first validate data
        // check campaign if campaign exist on database then next step will be continue
        $data = $request->validate([
            "id" => "required|exists:campaigns",
            "status" => "required|numeric"
        ]);
        // last step will be update status

        $campaign = Campaign::where("id", $data['id'])->update([
            "status" => $data['status'] == 1 ? 'publish' : 'draft'
        ]);

        return response()->json($campaign ? ["status" => true,"msg" => __("Campaign updated successfully")] : ["status" => false,"msg" => __("Failed to update something went wrong")]);
    }

}