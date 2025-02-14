<?php

namespace Modules\CountryManage\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Modules\CountryManage\Entities\Country;
use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CountryManage\Http\Requests\StoreCountryManageRequest;
use Modules\CountryManage\Http\Requests\UpdateCountryManageRequest;

class CountryManageController extends Controller
{
    const BASE_URL = 'backend.country.';
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $all_countries = Country::all();
        return view('countrymanage::backend.all-country', compact('all_countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCountryManageRequest $request
     * @return RedirectResponse
     */
    public function store(StoreCountryManageRequest $request): RedirectResponse
    {
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
     * @param UpdateCountryManageRequest $request
     * @return Response
     */
    public function update(UpdateCountryManageRequest $request): RedirectResponse
    {
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
     * @param Country $item
     * @return RedirectResponse
     */
    public function destroy(Country $item): RedirectResponse
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
