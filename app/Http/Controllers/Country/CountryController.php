<?php

namespace Modules\CountryManage\Http\Controllers\Country;

use Modules\CountryManage\Entities\Country;
use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CountryController extends Controller
{
    const BASE_URL = 'backend.country.';

    public function __construct()
    {
        $this->middleware('auth:admin')->except(['getCountryInfo', 'getStateInfo']);
        $this->middleware('permission:country-list|country-create|country-edit|country-delete', ['only', ['index']]);
        $this->middleware('permission:country-create', ['only', ['store']]);
        $this->middleware('permission:country-edit', ['only', ['update']]);
        $this->middleware('permission:country-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_countries = Country::all();

        return view(self::BASE_URL.'all-country', compact('all_countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'status' => 'required|string|max:191',
        ]);

        $country = Country::create([
            'name' => $request->sanitize_html('name'),
            'status' => $request->sanitize_html('status'),
        ]);

        return $country->id
            ? back()->with(FlashMsg::create_succeed('Country'))
            : back()->with(FlashMsg::create_failed('Country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\CountryManage\Entities\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'status' => 'required|string|max:191',
        ]);

        $updated = Country::findOrFail($request->id)->update([
            'name' => $request->sanitize_html('name'),
            'status' => $request->sanitize_html('status'),
        ]);

        return $updated
            ? back()->with(FlashMsg::update_succeed('Country'))
            : back()->with(FlashMsg::update_failed('Country'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modules\CountryManage\Entities\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $item)
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed('Country'))
            : back()->with(FlashMsg::delete_failed('Country'));
    }

    public function bulk_action(Request $request)
    {
        $deleted = Country::whereIn('id', $request->ids)->delete();
        if ($deleted) {
            return 'ok';
        }
    }
}
