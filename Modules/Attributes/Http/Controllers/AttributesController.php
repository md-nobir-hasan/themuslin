<?php

namespace Modules\Attributes\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\ProductAttribute;

class AttributesController extends Controller
{
    private const BASE_PATH = 'attributes::backend.attribute.';

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $all_attributes = ProductAttribute::all();

        return view(self::BASE_PATH . "all-attribute", compact('all_attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view(self::BASE_PATH . 'new-attribute');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'terms' => 'required|array',
        ]);

        $product_attribute = ProductAttribute::create([
            'title' => $request->title,
            'terms' => json_encode($request->terms)
        ]);

        return $product_attribute->id
            ? back()->with(FlashMsg::create_succeed('Product Attribute'))
            : back()->with(FlashMsg::create_failed('Product Attribute'));
    }

    /**
     * Display the specified resource.
     *
     * @param ProductAttribute $productAttribute
     * @return void
     */
    public function show(ProductAttribute $productAttribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductAttribute $item
     * @return Application|Factory|View
     */
    public function edit(ProductAttribute $item)
    {
        return view(self::BASE_PATH . 'edit-attribute')->with(['attribute' => $item]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ProductAttribute $productAttribute
     * @return RedirectResponse
     */
    public function update(Request $request, ProductAttribute $productAttribute): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string',
            'terms' => 'required|array',
        ]);
        $updated = ProductAttribute::find($request->id)->update([
            'title' => $request->title,
            'terms' => json_encode($request->terms)
        ]);

        return $updated
            ? back()->with(FlashMsg::update_succeed('Product Attribute'))
            : back()->with(FlashMsg::update_failed('Product Attribute'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductAttribute $item
     * @return bool|null
     */
    public function destroy(ProductAttribute $item): ?bool
    {
        return $item->delete();
    }

    /**
     * Bulk delete
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function bulk_action(Request $request)
    {
        ProductAttribute::whereIn('id', $request->ids)->delete();
        return back()->with(FlashMsg::item_delete());
    }

    /**
     * Get product attribute detail in JSON format
     *
     * @param Request $request (id)
     * @return JsonResponse
     */
    public function get_details(Request $request): JsonResponse
    {
        $variant = ProductAttribute::findOrFail($request->id);
        return response()->json($variant);
    }
}
