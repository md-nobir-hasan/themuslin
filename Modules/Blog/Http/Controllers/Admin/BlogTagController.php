<?php

namespace Modules\Blog\Http\Controllers\Admin;
use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Blog\Entities\BlogTag;
use function Modules\Blog\Http\Controllers\Landlord\Admin\create_slug;

class BlogTagController extends Controller
{
    public $languages = null;
    private const BASE_PATH = 'blog::landlord.admin.blog.';

    public function __construct()
    {
        $this->middleware('permission:blog-tag-list|blog-tag-create|blog-tag-edit|blog-tag-delete',['only' => ['index']]);
        $this->middleware('permission:blog-tag-create',['only' => ['new_tag']]);
        $this->middleware('permission:blog-tag-edit',['only' => ['update_tag']]);
        $this->middleware('permission:blog-tag-delete',['only' => ['delete_tag','bulk_action','delete_category_all_lang']]);
    }

    public function index(){
        $all_tag = BlogTag::select(['id','title', 'slug'])->get();
        return view(self::BASE_PATH.'tag')->with([
            'all_tag' => $all_tag
        ]);
    }

    public function new_tag(Request $request){
        $request->validate([
            'title' => 'required|string|max:191|unique:blog_tags',
            'slug' => 'nullable|string',
        ]);

        $tag = new BlogTag();
        $tag->title = SanitizeInput::esc_html($request->title);

        $slug = create_slug($request->slug ?? $request->title, 'BlogTag', true, 'Blog');

        $tag->slug = $slug;
        $tag->save();
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update_tag(Request $request){
        $request->validate([
            'title' => 'required|string|max:191',
            'slug' => 'nullable|string',
        ]);

        $tag =  BlogTag::findOrFail($request->id);
        $tag->title = SanitizeInput::esc_html($request->title);

        if ($tag->slug != $request->slug)
        {
            $slug = create_slug($request->slug ?? $request->title, 'BlogTag', true, 'Blog');
            $tag->slug = $slug;
        }

        $tag->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function delete_tag_all_lang(Request $request,$id){
        $category =  BlogTag::where('id',$id)->first();
        $category->delete();

        return response()->danger(ResponseMessage::delete());
    }


    public function bulk_action(Request $request){
        BlogTag::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }


    public function get_tags_by_ajax(Request $request)
    {
        $query = $request->get('query');
        $filterResult = BlogTag::Where('title', 'LIKE', '%' . $query . '%')->get();
        $html_markup = '';
        $result = [];
        foreach ($filterResult as $data) {
            array_push($result, $data->title);
        }

        return response()->json(['result' => $result]);
    }
}
