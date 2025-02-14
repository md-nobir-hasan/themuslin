<?php

namespace Modules\Product\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\ProductSize;

class ProductSizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-size-list|product-size-create|product-size-edit|product-size-delete', ['only', ['index']]);
        $this->middleware('permission:product-size-create', ['only', ['store']]);
        $this->middleware('permission:product-size-edit', ['only', ['update']]);
        $this->middleware('permission:product-size-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $product_sizes = ProductSize::all();
        return view('product::backend.all-size', compact('product_sizes'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'size_code' => 'required|string|max:191',
            'slug' => 'required|string|max:191',
        ]);

        $product_size = ProductSize::create([
            'name' => $request->name,
            'size_code' => $request->size_code,
            'slug' => $request->slug,
        ]);

        return $product_size
            ? back()->with(FlashMsg::create_succeed('Product Size'))
            : back()->with(FlashMsg::create_failed('Product Size'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'size_code' => 'required|string|max:191',
            'slug' => 'required|string|max:191',
        ]);

        $product_size = ProductSize::find($request->id);

        if (!$product_size) {
            return back()->with(FlashMsg::explain('danger', __('Items not found')));
        }

        $product_size = $product_size->update([
            'name' => $request->name,
            'size_code' => $request->size_code,
            'slug' => $request->slug,
        ]);

        return $product_size
            ? back()->with(FlashMsg::update_succeed('Product Size'))
            : back()->with(FlashMsg::update_failed('Product Size'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): \Illuminate\Http\RedirectResponse
    {
        $product_size = ProductSize::find($request->id);

        if (!$product_size) {
            return back()->with(FlashMsg::explain('danger', __('Items not found')));
        }

        return $product_size->delete()
            ? back()->with(FlashMsg::delete_succeed('Product Size'))
            : back()->with(FlashMsg::delete_failed('Product Size'));
    }

    public function bulk_action(Request $request){
        ProductSize::where('id', $request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
