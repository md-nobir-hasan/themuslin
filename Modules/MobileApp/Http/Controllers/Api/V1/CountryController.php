<?php

namespace Modules\MobileApp\Http\Controllers\Api\V1;

use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use App\Http\Traits\Api\CountryManageApiTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CountryManage\Entities\City;

final class CountryController extends Controller
{
    use CountryManageApiTrait;


    /*
    * fetch all country lists from a database
    */
    public function getCountries(Request $request): JsonResponse
    {
        // before change please mind it this method is also used on vendor api
        $country = Country::select('id', 'name')
            ->when($request->has('name'), function ($query) use ($request) {
                $query->where("name","LIKE", "%" . strip_tags($request->only("name")['name']) ."%");
            })
            ->orderBy('name', 'asc')->get();

        return response()->json([
            'countries' => $country,
        ]);
    }

    /*
    * fetch all state list based on provided country id from a database
    */
    public function getStateByCountryId($id, Request $request)
    {
        // before change please mind it this method is also used on vendor api
        if(empty($id)){
            return response()->json([
                'message' => __('provide a valid country id'),
            ])->setStatusCode(422);
        }

        $state = State::select('id', 'name','country_id')
            ->when($request->has('name'), function ($query) use ($request) {
                $query->where("name","LIKE", "%" . strip_tags($request->only("name")['name']) ."%");
            })
            ->where('country_id',$id)
            ->orderBy('name', 'asc')->get();

        return response()->json([
            'state' => $state,
        ]);
    }

    public function getCityByCountryId(Request $request, $id){
        // before change please mind it this method is also used on vendor api
        if(empty($id)){
            return response()->json([
                'message' => __('provide a valid country id'),
            ])->setStatusCode(422);
        }

        $cities = City::select('id', 'name','state_id')
            ->when($request->has('name'), function ($query) use ($request) {
                $query->where("name","LIKE", "%" . strip_tags($request->only("name")['name']) ."%");
            })->where('state_id',$id)->orderBy('name', 'asc')->get();

        return response()->json([
            'cities' => $cities,
        ]);
    }
}
