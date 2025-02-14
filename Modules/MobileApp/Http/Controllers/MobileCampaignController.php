<?php

namespace Modules\MobileApp\Http\Controllers;

use Modules\Campaign\Entities\Campaign;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\MobileApp\Entities\MobileCampaign;

class MobileCampaignController extends Controller
{
    public function __construct(){
        $this->middleware(["auth:admin"]);
    }

    public function index(){
        $campaigns = Campaign::select('title as name','id')->get();
        $selectedCampaign = MobileCampaign::first();

        return view("mobileapp::mobile-campaign.create",compact('campaigns','selectedCampaign'));
    }

    public function update(Request $request){
        $data = $request->validate(["campaign" => 'required']);

        MobileCampaign::updateOrCreate(['id' => 1] ,['type' => '1', 'campaign_id' => $data['campaign']]);

        return back()->with(["type" => 'success' , 'msg' => "Successfully campaign updated"]);
    }
}
