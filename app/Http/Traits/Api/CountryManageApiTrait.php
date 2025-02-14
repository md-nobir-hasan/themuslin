<?php

namespace App\Http\Traits\Api;

use Modules\CountryManage\Entities\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\CountryManage\Entities\City;
use Modules\CountryManage\Entities\Country;

trait CountryManageApiTrait
{
   /*
    * fetch all country lists from a database
    */
    public function country(Request $request): JsonResponse
    {
        // before change please mind it this method is also used on vendor api
        $country = Country::select('id', 'name')
            ->when($request->has('name'), function ($query) use ($request) {
                $query->where("name","LIKE", "%" . strip_tags($request->only("name")['name']) ."%");
            })
            ->orderBy('name', 'asc')->paginate(20);

        return response()->json([
            'countries' => $country,
        ]);
    }

    /*
    * fetch all state list based on provided country id from a database
    */
    public function stateByCountryId($id, Request $request)
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
            ->orderBy('name', 'asc')->paginate(20);

        return response()->json([
            'state' => $state,
        ]);
    }

    public function cityByCountryId(Request $request, $id){
        // before change please mind it this method is also used on vendor api
        if(empty($id)){
            return response()->json([
                'message' => __('provide a valid country id'),
            ])->setStatusCode(422);
        }

        $cities = City::select('id', 'name','state_id')
            ->when($request->has('name'), function ($query) use ($request) {
                $query->where("name","LIKE", "%" . strip_tags($request->only("name")['name']) ."%");
            })->where('state_id',$id)->orderBy('name', 'asc')->paginate(20);

        return response()->json([
            'state' => $cities,
        ]);
    }
}