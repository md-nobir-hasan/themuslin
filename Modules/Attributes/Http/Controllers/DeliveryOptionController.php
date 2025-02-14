<?php

namespace Modules\Attributes\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\DeliveryOption;

class DeliveryOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(): Renderable
    {
        $delivery_manages = DeliveryOption::all();
        return view('attributes::backend.delivery-option.index', compact('delivery_manages'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:191|unique:delivery_options',
            'sub_title' => 'required|string|max:191',
            'icon' => 'required|string|max:191'
        ]);

        $unit = DeliveryOption::create($data);
        return $unit
            ? back()->with(FlashMsg::create_succeed('Product Unit'))
            : back()->with(FlashMsg::create_failed('Product Unit'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $unit = DeliveryOption::findOrFail($request->id)->update($request->validate([
            'title' => 'required|exists:delivery_options',
            'sub_title' => 'required|string|max:191',
            'icon' => 'required|string|max:191'
        ]));

        return $unit
            ? back()->with(FlashMsg::update_succeed('Product Unit'))
            : back()->with(FlashMsg::update_failed('Product Unit'));
    }

    /**
     * Remove the specified resource from storage.
     * @param DeliveryOption $item
     * @return RedirectResponse
     */
    public function destroy(DeliveryOption $item): RedirectResponse
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed('Product Unit'))
            : back()->with(FlashMsg::delete_failed('Product Unit'));
    }

    /**
     * Remove all the specified resources from storage.
     * @param Request $request
     * @return boolean
     */
    public function bulk_action(Request $request): bool
    {
        $units = DeliveryOption::whereIn('id', $request->ids)->get();
        foreach ($units as $unit) {
            $unit->delete();
        }
        return true;
    }
}
