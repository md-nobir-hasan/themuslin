<?php

namespace Modules\CountryManage\Http\Controllers\Shipping;

use Modules\CountryManage\Entities\Country;
use App\Helpers\FlashMsg;
use App\Shipping\Zone;
use App\Shipping\ZoneRegion;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ZoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:shipping-zone-list|shipping-zone-create|shipping-zone-edit|shipping-zone-delete', ['only', ['index']]);
        $this->middleware('permission:shipping-zone-create', ['only', ['store']]);
        $this->middleware('permission:shipping-zone-edit', ['only', ['update']]);
        $this->middleware('permission:shipping-zone-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_zones = Zone::all();
        $all_countries = Country::where('status', 'publish')->get();

        return view('backend.shipping.zone.all', compact('all_zones', 'all_countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'country' => 'nullable|array',
            'state' => 'nullable|array',
        ]);

        $zone = Zone::create(['name' => $request->name]);
        $zone_region = ZoneRegion::create([
            'zone_id' => $zone->id,
            'country' => json_encode(sanitizeArray($request->country)),
            'state' => json_encode(sanitizeArray($request->state)),
        ]);

        return $zone->id
            ? back()->with(FlashMsg::create_succeed('Zone'))
            : back()->with(FlashMsg::create_failed('Zone'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'country' => 'nullable|array',
            'state' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();
            $zone_updated = Zone::find($request->id)->update(['name' => $request->name]);

            ZoneRegion::where('zone_id', $request->id)->delete();

            $zone_region = ZoneRegion::create([
                'zone_id' => $request->id,
                'country' => json_encode(sanitizeArray($request->country)),
                'state' => json_encode(sanitizeArray($request->state)),
            ]);
            DB::commit();

            return back()->with(FlashMsg::update_succeed('Zone'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->with(FlashMsg::update_failed('Zone'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zone $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            ZoneRegion::where('zone_id', $item->id)->delete();
            DB::commit();

            return back()->with(FlashMsg::delete_succeed('Zone'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->with(FlashMsg::delete_failed('Zone'));
        }
    }

    public function bulk_action(Request $request)
    {
        try {
            DB::beginTransaction();
            Zone::whereIn('id', $request->ids)->delete();
            ZoneRegion::whereIn('zone_id', $request->ids)->delete();
            DB::commit();

            return FlashMsg::delete_succeed('Zone');
        } catch (\Throwable $th) {
            DB::rollBack();

            return FlashMsg::delete_failed('Zone');
        }
    }
}
