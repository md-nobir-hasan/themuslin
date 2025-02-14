<?php

namespace Modules\TaxModule\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Modules\CountryManage\Entities\Country;
use App\Helpers\FlashMsg;
use Modules\TaxModule\Entities\StateTax;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class StateTaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $all_country = Country::where('status', 'publish')->get();
        $all_state_tax = StateTax::with('state')->get();
        return view('taxmodule::backend.state-tax', compact('all_state_tax', 'all_country'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'state_id' => 'required|unique:state_taxes',
            'tax_percentage' => 'required|string|max:191',
            'country_id' => 'required|string|max:191',
        ]);

        $state_tax = StateTax::create([
            'state_id' => $request->state_id,
            'tax_percentage' => $request->tax_percentage,
            'country_id' => $request->country_id,
        ]);

        return $state_tax->id
            ? back()->with(FlashMsg::create_succeed('State Tax'))
            : back()->with(FlashMsg::create_failed('State Tax'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'state_id' => 'required',
            'tax_percentage' => 'required|string|max:191',
            'country_id' => 'required|string|max:191',
        ]);

        $updated = StateTax::findOrFail($request->id)->update([
            'state_id' => $request->state_id,
            'tax_percentage' => $request->tax_percentage,
            'country_id' => $request->country_id,
        ]);

        return $updated
            ? back()->with(FlashMsg::update_succeed('State Tax'))
            : back()->with(FlashMsg::update_failed('State Tax'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param StateTax $item
     * @return RedirectResponse
     */
    public function destroy(StateTax $item)
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed('State Tax'))
            : back()->with(FlashMsg::delete_failed('State Tax'));
    }

    public function bulk_action(Request $request)
    {
        $deleted = StateTax::where('id', $request->ids)->delete();
        if ($deleted) {
            return 'ok';
        }
    }
}
