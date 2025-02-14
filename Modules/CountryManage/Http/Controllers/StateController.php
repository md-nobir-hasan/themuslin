<?php

namespace Modules\CountryManage\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CountryManage\Http\Requests\UpdateStateRequest;

class StateController extends Controller
{
    private const BASE_URL = "countrymanage::backend.";
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $all_countries = Country::all();
        $all_states = State::with('country')->paginate(20);
        return view(self::BASE_URL.'all-state', compact('all_countries', 'all_states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $state = State::create([
            'name' => $request->sanitize_html('name'),
            'country_id' => $request->sanitize_html('country_id'),
            'status' => $request->sanitize_html('status'),
        ]);

        return $state->id
            ? back()->with(FlashMsg::create_succeed('State'))
            : back()->with(FlashMsg::create_failed('State'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Modules\CountryManage\Entities\State  $state
     * @return Response
     */
    public function update(UpdateStateRequest $request, State $state)
    {
        $updated = State::findOrFail($request->id)->update([
            'name' => $request->sanitize_html('name'),
            'country_id' => $request->sanitize_html('country_id'),
            'status' => $request->sanitize_html('status'),
        ]);

        return $updated
            ? back()->with(FlashMsg::create_succeed('State'))
            : back()->with(FlashMsg::create_failed('State'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modules\CountryManage\Entities\State  $state
     * @return Response
     */
    public function destroy(State $item)
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed('State'))
            : back()->with(FlashMsg::delete_failed('State'));
    }

    public function bulk_action(Request $request)
    {
        $deleted = State::whereIn('id', $request->ids)->delete();
        if ($deleted) {
            return 'ok';
        }
    }

    public function getStateByCountry(Request $request)
    {
        $request->validate(['id' => 'required|exists:countries']);
        return State::select('id', 'name')
            ->where('country_id', $request->id)
            ->where('status', 'publish')
            ->get();
    }
    public function getMultipleStateByCountry(Request $request)
    {
        $request->validate(['id' => 'required']);

        return State::select('id', 'name')
            ->whereIn('country_id', $request->id)
            ->where('status', 'publish')
            ->get();
    }
}
