<?php

namespace Modules\CountryManage\Http\Controllers\Product;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Attributes\Http\Requests\StoreCategoryRequest;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Http\Requests\StoreCategoryRequest;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-category-list|product-category-create|product-category-edit|product-category-delete', ['only', ['index']]);
        $this->middleware('permission:product-category-create', ['only', ['store']]);
        $this->middleware('permission:product-category-edit', ['only', ['update']]);
        $this->middleware('permission:product-category-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $all_category = ProductCategory::all();

        return view('backend.products.category.all')->with(['all_category' => $all_category]);
    }

    public function store(StoreCategoryRequest $request): Response
    {
        $product_category = ProductCategory::create($request->validated());

        return $product_category->id
            ? back()->with(FlashMsg::create_succeed(__('Product Category')))
            : back()->with(FlashMsg::create_failed(__('Product Category')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'status' => 'required|string|max:191',
            'image' => 'nullable|string|max:191',
        ]);

        $updated = ProductCategory::find($request->id)->update([
            'title' => $request->title,
            'status' => $request->status,
            'image' => $request->image,
        ]);

        return $updated
            ? back()->with(FlashMsg::update_succeed(__('Product Category')))
            : back()->with(FlashMsg::update_failed(__('Product Category')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modules\Product\Entities\ProductCategory  $productCategory
     * @return Response
     */
    public function destroy(ProductCategory $item)
    {
        return $item->delete();
    }

    public function bulk_action(Request $request)
    {
        ProductCategory::WhereIn('id', $request->ids)->delete();

        return response()->json(['status' => 'ok']);
    }
}
