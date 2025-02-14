<?php

namespace Modules\Attributes\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\ChildCategory;
use Modules\Attributes\Http\Requests\ChildCategoryStoreRequest;
use Modules\Attributes\Http\Requests\ChildCategoryUpdateRequest;

class ChildCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $all_category = Category::all();
        $all_child_category = ChildCategory::with("sub_category","category","image","status")->paginate(20);

        return view('attributes::backend.child-category.all', compact('all_category', 'all_child_category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChildCategoryStoreRequest $request
     * @return RedirectResponse
     */
    public function store(ChildCategoryStoreRequest $request): RedirectResponse
    {
        $product_category = ChildCategory::create($request->validated());

        return $product_category->id
            ? back()->with(FlashMsg::create_succeed(__('Product Child-Category')))
            : back()->with(FlashMsg::create_failed(__('Product Child-Category')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ChildCategoryUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(ChildCategoryUpdateRequest $request)
    {
        $updated = ChildCategory::where("id", $request->id)->update($request->validated());

        return $updated
            ? back()->with(FlashMsg::update_succeed(__('Product Child-Category')))
            : back()->with(FlashMsg::update_failed(__('Product Child-Category')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ChildCategory $item
     * @return bool|null
     */
    public function destroy(ChildCategory $item): ?bool
    {
        return $item->delete();
    }

    public function bulk_action(Request $request): JsonResponse
    {
        ChildCategory::WhereIn('id', $request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    public function getSubcategoriesOfCategory($id): JsonResponse
    {
        $all_subcategory = ChildCategory::where('category_id', $id)
            ->select("id","name")->get();
        return response()->json($all_subcategory);
    }
}
