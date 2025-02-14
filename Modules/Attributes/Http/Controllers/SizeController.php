<?php

namespace Modules\Attributes\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Attributes\Entities\Size;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return View|Factory
     */
    public function index(): Factory|View
    {
        $product_sizes = Size::all();
        return view('attributes::backend.size.all-size', compact('product_sizes'));
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
            'slug' => 'nullable|string|max:191',
        ]);

        $sluggable_text = $request->slug == null ? Str::slug(trim($request->name)) : Str::slug($request->slug);
        $slug = createslug($sluggable_text, model_name: 'Size',is_module: true, module_name: 'Attributes');
        $data['slug'] = $slug;

        $product_size = Size::create([
            'name' => $request->name,
            'size_code' => $request->size_code,
            'slug' => $data['slug'],
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

        $product_size = Size::findOrFail($request->id);

        if ($product_size->slug != $request->slug)
        {
            $sluggable_text = Str::slug($request->slug ?? $request->name);
            $new_slug = createslug($sluggable_text, 'Size', true, 'Attributes');
            $request['slug'] = $new_slug;
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
    public function destroy(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $product_size = Size::findOrFail($id);

        return $product_size->delete()
            ? back()->with(FlashMsg::delete_succeed('Product Size'))
            : back()->with(FlashMsg::delete_failed('Product Size'));
    }

    public function bulk_action(Request $request){
        Size::whereIn('id', $request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
