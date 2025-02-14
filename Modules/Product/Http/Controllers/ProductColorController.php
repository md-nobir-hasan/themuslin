<?php

namespace Modules\Product\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\ProductColor;

class ProductColorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-color-list|product-color-create|product-color-edit|product-color-delete', ['only', ['index']]);
        $this->middleware('permission:product-color-create', ['only', ['store']]);
        $this->middleware('permission:product-color-edit', ['only', ['update']]);
        $this->middleware('permission:product-color-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $product_colors = ProductColor::all();
        return view('product::backend..all-color', compact('product_colors'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'color_code' => 'required|string|max:191',
            'slug' => 'required|string|max:191',
        ]);

        $product_color = ProductColor::create([
            'name' => $request->name,
            'color_code' => $request->color_code,
            'slug' => $request->slug,
        ]);

        return $product_color
            ? back()->with(FlashMsg::create_succeed('Product Color'))
            : back()->with(FlashMsg::create_failed('Product Color'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'color_code' => 'required|string|max:191',
            'slug' => 'required|string|max:191',
        ]);

        $product_color = ProductColor::find($request->id);

        if (!$product_color) {
            return back()->with(FlashMsg::explain('danger', __('Items not found')));
        }

        $product_color = $product_color->update([
            'name' => $request->name,
            'color_code' => $request->color_code,
            'slug' => $request->slug,
        ]);

        return $product_color
            ? back()->with(FlashMsg::update_succeed('Product Color'))
            : back()->with(FlashMsg::update_failed('Product Color'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): \Illuminate\Http\RedirectResponse
    {
        $product_color = ProductColor::find($request->id);

        if (!$product_color) {
            return back()->with(FlashMsg::explain('danger', __('Items not found')));
        }

        return $product_color->delete()
            ? back()->with(FlashMsg::delete_succeed('Product Color'))
            : back()->with(FlashMsg::delete_failed('Product Color'));
    }

    public function bulk_action(Request $request){
        $all_product_colors = ProductColor::where('id', $request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
