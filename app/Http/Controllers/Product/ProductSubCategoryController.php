<?php

namespace Modules\CountryManage\Http\Controllers\Product;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductSubCategory;

class ProductSubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-subcategory-list|product-subcategory-create|product-subcategory-edit|product-subcategory-delete', ['only', ['index']]);
        $this->middleware('permission:product-subcategory-create', ['only', ['store']]);
        $this->middleware('permission:product-subcategory-edit', ['only', ['update']]);
        $this->middleware('permission:product-subcategory-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_category = ProductCategory::all();
        $all_subcategory = ProductSubCategory::all();

        return view('backend.products.subcategory.all', compact('all_category', 'all_subcategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191|unique:product_sub_categories',
            'status' => 'required|string|max:191',
            'image' => 'nullable|string|max:191',
            'category_id' => 'nullable|string|exists:product_categories,id',
        ]);

        $product_category = ProductSubCategory::create([
            'title' => $request->title,
            'status' => $request->status,
            'image' => $request->image,
            'category_id' => $request->category_id,
        ]);

        return $product_category->id
            ? back()->with(FlashMsg::create_succeed(__('Product Sub-Category')))
            : back()->with(FlashMsg::create_failed(__('Product Sub-Category')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductSubCategory $productSubCategory)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'status' => 'required|string|max:191',
            'image' => 'nullable|string|max:191',
            'category_id' => 'nullable|string|exists:product_categories,id',
        ]);

        $updated = ProductSubCategory::find($request->id)->update([
            'title' => $request->title,
            'status' => $request->status,
            'image' => $request->image,
            'category_id' => $request->category_id,
        ]);

        return $updated
            ? back()->with(FlashMsg::update_succeed(__('Product Sub-Category')))
            : back()->with(FlashMsg::update_failed(__('Product Sub-Category')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSubCategory $item)
    {
        return $item->delete();
    }

    public function bulk_action(Request $request)
    {
        ProductSubCategory::WhereIn('id', $request->ids)->delete();

        return response()->json(['status' => 'ok']);
    }

    public function getSubcategoriesOfCategory($id)
    {
        $all_subcategory = ProductSubCategory::where('category_id', $id)->get();

        return response()->json($all_subcategory);
    }
}
