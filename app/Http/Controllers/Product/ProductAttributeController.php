<?php

namespace Modules\CountryManage\Http\Controllers\Product;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\ProductAttribute;

class ProductAttributeController extends Controller
{
    const BASE_PATH = 'backend.products.attribute.';

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-attribute-list|product-attribute-create|product-attribute-edit|product-attribute-delete', ['only', ['index']]);
        $this->middleware('permission:product-attribute-create', ['only', ['create', 'store']]);
        $this->middleware('permission:product-attribute-edit', ['only', ['edit', 'update']]);
        $this->middleware('permission:product-attribute-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_attributes = ProductAttribute::all();

        return view(self::BASE_PATH.'all-attribute', compact('all_attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(self::BASE_PATH.'new-attribute');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'terms' => 'required|array',
        ]);

        $product_attribute = ProductAttribute::create([
            'title' => $request->title,
            'terms' => json_encode($request->terms),
        ]);

        return $product_attribute->id
            ? back()->with(FlashMsg::create_succeed('Product Attribute'))
            : back()->with(FlashMsg::create_failed('Product Attribute'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProductAttribute $productAttribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Modules\Product\Entities\ProductAttribute  $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductAttribute $item)
    {
        return view(self::BASE_PATH.'edit-attribute')->with(['attribute' => $item]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductAttribute $productAttribute)
    {
        $request->validate([
            'title' => 'required|string',
            'terms' => 'required|array',
        ]);
        $updated = ProductAttribute::find($request->id)->update([
            'title' => $request->title,
            'terms' => json_encode($request->terms),
        ]);

        return $updated
            ? back()->with(FlashMsg::update_succeed('Product Attribute'))
            : back()->with(FlashMsg::update_failed('Product Attribute'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modules\Product\Entities\ProductAttribute  $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductAttribute $item)
    {
        return $item->delete();
    }

    /**
     * Bulk delete
     *
     * @return \Illuminate\Http\Response
     */
    public function bulk_action(Request $request)
    {
        ProductAttribute::whereIn('id', $request->ids)->delete();

        return back()->with(FlashMsg::item_delete());
    }

    /**
     * Get product attribute detail in JSON format
     *
     * @param  \Illuminate\Http\Request  $request (id)
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_details(Request $request)
    {
        $variant = ProductAttribute::findOrFail($request->id);

        return response()->json($variant);
    }
}
