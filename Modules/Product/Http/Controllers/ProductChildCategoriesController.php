<?php

namespace Modules\Product\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductChildCategory;
use Modules\Product\Entities\ProductSubCategory;
use Modules\Product\Http\Requests\CreateSubCategoryRequest;
use Modules\Product\Http\Requests\ProductChildCategoryStoreRequest;
use Modules\Product\Http\Requests\ProductChildCategoryUpdateRequest;
use Modules\Product\Http\Requests\UpdateSubCategoryRequest;

class ProductChildCategoriesController extends Controller
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
     * @return Application|Factory|View
     */
    public function index()
    {
        $all_category = ProductCategory::all();
        $all_child_category = ProductChildCategory::with("sub_category","category","media","status")->get();

        return view('product::backend.child-category.all', compact('all_category', 'all_child_category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(ProductChildCategoryStoreRequest $request)
    {
        $product_category = ProductChildCategory::create($request->validated());

        return $product_category->id
            ? back()->with(FlashMsg::create_succeed(__('Product Sub-Category')))
            : back()->with(FlashMsg::create_failed(__('Product Sub-Category')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSubCategoryRequest $request
     * @param ProductSubCategory $productSubCategory
     * @return RedirectResponse
     */
    public function update(ProductChildCategoryUpdateRequest $request)
    {
        $updated = ProductChildCategory::where("id", $request->id)->update($request->validated());

        return $updated
            ? back()->with(FlashMsg::update_succeed(__('Product Sub-Category')))
            : back()->with(FlashMsg::update_failed(__('Product Sub-Category')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductChildCategory $item
     * @return bool|null
     */
    public function destroy(ProductChildCategory $item)
    {
        return $item->delete();
    }

    public function bulk_action(Request $request){
        ProductChildCategory::WhereIn('id', $request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    public function getSubcategoriesOfCategory($id): \Illuminate\Http\JsonResponse
    {
        $all_subcategory = ProductChildCategory::where('category_id', $id)->select("id","name")->get();
        return response()->json($all_subcategory);
    }
}
