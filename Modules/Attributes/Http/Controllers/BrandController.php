<?php

namespace Modules\Attributes\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\Brand;
use Modules\Attributes\Http\Requests\BrandStoreRequest;

class BrandController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(): Renderable
    {
        $brands = Brand::with(["logo", "banner"])->latest()->get();

        return view('attributes::backend.brand.index', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     * @param BrandStoreRequest $request
     * @return RedirectResponse
     */
    public function store(BrandStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $brand = Brand::create($data);
        return $brand
            ? back()->with(FlashMsg::create_succeed('Brand'))
            : back()->with(FlashMsg::create_failed('Brand'));
    }

    /**
     * Update the specified resource in storage.
     * @param BrandStoreRequest $request
     * @return RedirectResponse
     */
    public function update(BrandStoreRequest $request): RedirectResponse
    {
        $brand = Brand::findOrFail($request->id)->update($request->validated());

        return $brand
            ? back()->with(FlashMsg::update_succeed('Brand'))
            : back()->with(FlashMsg::update_failed('Brand'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Brand $item
     * @return RedirectResponse
     */
    public function destroy(Brand $item): RedirectResponse
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed('Brand'))
            : back()->with(FlashMsg::delete_failed('Brand'));
    }

    /**
     * Remove all the specified resources from storage.
     * @param Request $request
     * @return boolean
     */
    public function bulk_action(Request $request): bool
    {
        $units = Brand::whereIn('id', $request->ids)->get();
        foreach ($units as $unit) {
            $unit->delete();
        }
        return true;
    }
}
