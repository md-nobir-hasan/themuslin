<?php

namespace Modules\Product\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\ProductUnit;

class ProductUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->middleware('permission:product-unit-list|product-unit-create|product-unit-edit|product-unit-delete', ['only', ['index']]);
        $this->middleware('permission:product-unit-create', ['only', ['store']]);
        $this->middleware('permission:product-unit-edit', ['only', ['update']]);
        $this->middleware('permission:product-unit-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $product_units = ProductUnit::all();
        return view('product::unit.index', compact('product_units'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Redirect
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:product_units'
        ]);

        $unit = ProductUnit::create(['name' => sanitize_html($request->name)]);
        return $unit
            ? back()->with(FlashMsg::create_succeed('Product Unit'))
            : back()->with(FlashMsg::create_failed('Product Unit'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|exists:product_units',
            'name' => 'required|string|max:191'
        ]);

        $unit = ProductUnit::findOrFail($request->id)->update([
            'name' => $request->name
        ]);

        return $unit
            ? back()->with(FlashMsg::update_succeed('Product Unit'))
            : back()->with(FlashMsg::update_failed('Product Unit'));
    }

    /**
     * Remove the specified resource from storage.
     * @param ProductUnit $item
     * @return array
     */
    public function destroy(ProductUnit $item)
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed('Product Unit'))
            : back()->with(FlashMsg::delete_failed('Product Unit'));
    }

    /**
     * Remove all the specified resources from storage.
     * @param int $id
     * @return boolean
     */
    public function bulk_action(Request $request)
    {
        $units = ProductUnit::whereIn('id', $request->ids)->get();
        foreach ($units as $unit) {
            $unit->delete();
        }
        return true;
    }
}
