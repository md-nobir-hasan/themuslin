<?php

namespace Modules\ShippingModule\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\CountryManage\Entities\Country;
use Modules\ShippingModule\Entities\Zone;
use Modules\ShippingModule\Entities\ZoneCountry;
use Modules\ShippingModule\Entities\ZoneRegion;
use Modules\ShippingModule\Entities\ZoneState;
use Modules\ShippingModule\Http\Requests\StoreShippingZoneRequest;

class ZoneController extends Controller
{
    public function index(){
        $data = [
           "zones" => Zone::with("country","country.zoneStates")->get(),
        ];

        return view("shippingmodule::admin.index", $data);
    }

    public function create(){
        $data = [
           "countries" => Country::select("id","name")->get(),
        ];

        return view("shippingmodule::admin.create", $data);
    }

    public function store(StoreShippingZoneRequest $request){
        $data = $request->validated();

        $zone = Zone::create(["name" => $data["zone_name"]]);
        $this->insertAllCountryAndStates($request,$zone);

        return response()->json([
            "success" => true,"type" => "success",
            "msg" => __("Successfully inserted country and states")
        ]);
    }

    public function edit($id){
        $data = [
            "zone" => Zone::with(["country","country.zoneStates","country.states"])->where("id", $id)->firstOrFail(),
            "countries" => Country::select("id","name")->get(),
            "id" => $id
        ];

        return view("shippingmodule::admin.edit", $data);
    }

    public function update(StoreShippingZoneRequest $request, $id){
        $data = $request->validated();
        // First delete all previous country and states
        $this->deleteAllCountryStatesAndZone($id);

        // update zone
        Zone::where("id", $id)->update(["name" => $data["zone_name"]]);
        // fetch zone
        $zone = Zone::where("id", $id)->first();
        // now insert all country and states as like store method do
        $this->insertAllCountryAndStates($request,$zone);

        // send json response for frontend
        return response()->json([
            "success" => true,"type" => "success",
            "msg" => __("Successfully updated country and states")
        ]);
    }

    public function destroy($id){
        // first step is remove all zone country and zone
        // second step is remove zone
        $this->deleteAllCountryStatesAndZone($id,"delete");

        // return redirect back with success message
        return back()->with(["msg" => "Successfully deleted zone"]);
    }

    public function deleteAllCountryStatesAndZone($zoneId, $type = "update"){
        // delete all country and state
        // if zone country deleted than automatically zone state will be deleted
        ZoneCountry::where("zone_id", $zoneId)->delete();

        // check type if it is delete than delete zone
        if($type == 'delete'){
            Zone::where("id", $zoneId)->delete();
        }

        // no use is this return but return something is better than void
        return true;
    }

    /**
     * @param $data
     * @param $zone
     * @return bool
     */
    private function insertAllCountryAndStates($data,$zone): bool
    {
        // initialize a temporary variable for storing states those states will be bulk insert
        $states = [];
        // now run the for every country those are got from request
        foreach ($data["country"] as $countryInt) {
            // create zone country with request country id and zone id from create or update method
            $country = ZoneCountry::create([
                "zone_id" => $zone->id,
                "country_id" => $countryInt
            ]);
            //
            foreach ($data["states"][$countryInt] ?? [] as $state) {
                $states[] = [
                    "zone_country_id" => $country->id,
                    "state_id" => $state
                ];
            }
        }

        !empty($states) ? ZoneState::insert($states) : '';

        return true;
    }
}
