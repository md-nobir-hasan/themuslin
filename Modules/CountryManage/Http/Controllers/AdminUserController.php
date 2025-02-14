<?php

namespace Modules\CountryManage\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CountryManage\Entities\City;
use Modules\CountryManage\Entities\State;

class AdminUserController extends Controller
{
    //todo: get state
    public function get_country_state(Request $request)
    {
        $states = State::where('country_id', $request->country)
            ->select("id","name")
            ->where('status', 'publish')->get();

        return response()->json([
            'status' => 'success',
            'states' => $states,
        ]);
    }

    //todo: get city
    public function get_state_city(Request $request)
    {
        $cities = City::where('state_id', $request->state)->where('status', 1)->get();

        return response()->json([
            'status' => 'success',
            'cities' => $cities,
        ]);
    }
}
