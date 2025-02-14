<?php

namespace Modules\Product\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductSubCategory;
use Modules\Product\Http\Requests\CreateSubCategoryRequest;
use Modules\Product\Http\Requests\UpdateSubCategoryRequest;
use function __;
use function back;
use function response;
use function view;

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
     * @return Response
     */
    public function index()
    {
        $all_category = ProductCategory::all();
        $all_subcategory = ProductSubCategory::with("category","media")->get();

        return view('product::backend.subcategory.all', compact('all_category', 'all_subcategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(CreateSubCategoryRequest $request)
    {
        $product_category = ProductSubCategory::create($request->validated());

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
    public function update(UpdateSubCategoryRequest $request)
    {
        $updated = ProductSubCategory::where("id", $request->id)->update($request->validated());
        return $updated
            ? back()->with(FlashMsg::update_succeed(__('Product Sub-Category')))
            : back()->with(FlashMsg::update_failed(__('Product Sub-Category')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product\ProductSubCategory  $item
     * @return Response
     */
    public function destroy(ProductSubCategory $item)
    {
        return $item->delete();
    }

    public function bulk_action(Request $request){
        ProductSubCategory::WhereIn('id', $request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    public function getSubcategoriesOfCategory($id)
    {
        $all_subcategory = ProductSubCategory::where('category_id', $id)->get();
        return response()->json($all_subcategory);
    }

    public function getSubcategoriesForSelect($id){
        $all_subcategory = ProductSubCategory::where('category_id', $id)->get();

        // create option in hare
        $option = "";
        $list = "";
        foreach($all_subcategory as $sub_category){
            $option .= "<option value='" . $sub_category->id . "'>". $sub_category->name ."</option>";
            $list .= "<li data-value='". $sub_category->id ."' class='option'>". $sub_category->name ."</li>";//$sub_category->name;
        }

        return response()->json(["option" => $option,"list" => $list]);
    }
}
