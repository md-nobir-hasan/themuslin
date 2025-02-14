<?php

namespace Modules\Product\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Tag;

use function __;
use function back;
use function response;
use function view;

class TagController extends Controller
{
    // product-tag-list
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-tag-list|product-tag-create|product-tag-edit|product-tag-delete', ['only', ['index']]);
        $this->middleware('permission:product-tag-create', ['only', ['store']]);
        $this->middleware('permission:product-tag-edit', ['only', ['update']]);
        $this->middleware('permission:product-tag-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_tag = Tag::all();

        return view('product::backend.tag.all-tag', compact('all_tag'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string|max:191|unique:tags,tag_text']);
        $tag = Tag::create(['tag_text' => $request->title]);

        return $tag->id
            ? back()->with(FlashMsg::create_succeed(__('Tag')))
            : back()->with(FlashMsg::create_failed(__('Tag')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Product\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate(['title' => 'required|string|max:191|exists:tags,tag_text']);
        $updated = Tag::find($request->id)->update(['tag_text' => $request->title]);

        return $updated
            ? back()->with(FlashMsg::update_succeed(__('Tag')))
            : back()->with(FlashMsg::update_failed(__('Tag')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $item)
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed(__('Tag')))
            : back()->with(FlashMsg::delete_failed(__('Tag')));
    }

    public function bulk_action(Request $request)
    {
        Tag::WhereIn('id', $request->ids)->delete();

        return response()->json(['status' => 'ok']);
    }

    public function getTagsAjax(Request $request)
    {
        $request->validate(['tag_query' => 'nullable|string|max:191']);
        $tags = Tag::select('id', 'tag_text as tag')->where('tag_text', 'LIKE', "%$request->tag_query%")->get();

        return response()->json(['result' => $tags], 200);
    }
}
