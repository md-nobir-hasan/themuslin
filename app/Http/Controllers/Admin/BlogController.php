<?php

namespace App\Http\Controllers\Admin;

use App\Blog;
use App\User;
use App\BlogCategory;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    private $base_path = 'backend.blog.';

    public function index()
    {
        $all_blog = Blog::all();

        return view($this->base_path.'index')->with([
            'all_blog' => $all_blog,
        ]);
    }

    public function new_blog()
    {
        $all_category = BlogCategory::all();

        return view($this->base_path.'new')->with([
            'all_category' => $all_category,
        ]);
    }

    public function store_new_blog(Request $request)
    {
        $request->validate([
            'category' => 'nullable',
            'blog_content' => 'required',
            'tags' => 'nullable',
            'excerpt' => 'nullable',
            'title' => 'required',
            'status' => 'required',
            'author' => 'nullable',
            'slug' => 'nullable',
            'meta_tags' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'og_meta_title' => 'nullable|string',
            'og_meta_description' => 'nullable|string',
            'og_meta_image' => 'nullable|string',
            'image' => 'nullable|string|max:191',
        ]);

        Blog::create([
            'blog_categories_id' => $request->category,
            'slug' => ! empty($request->slug) ? \Str::slug($request->slug) : \Str::slug($request->title),
            'blog_content' => purify_html_raw($request->blog_content),
            'tags' => purify_html($request->tags),
            'title' => purify_html($request->title),
            'status' => $request->status,
            'meta_tags' => purify_html($request->meta_tags),
            'meta_title' => purify_html($request->meta_title),
            'meta_description' => purify_html($request->meta_description),
            'excerpt' => purify_html($request->excerpt),
            'image' => $request->image,
            'user_id' => Auth::user()->id,
            'author' => Auth::user()->name,
            'og_meta_title' => purify_html($request->og_meta_title),
            'og_meta_description' => purify_html($request->og_meta_description),
            'og_meta_image' => $request->og_meta_image,
        ]);

        return redirect()->back()->with([
            'msg' => __('New Item Added...'),
            'type' => 'success',
        ]);
    }

    public function clone_blog(Request $request)
    {
        $blog_details = Blog::findOrFail($request->item_id);
        Blog::create([
            'blog_categories_id' => $blog_details->blog_categories_id,
            'slug' => ! empty($blog_details->slug) ? \Str::slug($blog_details->slug) : \Str::slug($blog_details->title),
            'blog_content' => $blog_details->blog_content,
            'tags' => $blog_details->tags,
            'title' => $blog_details->title,
            'status' => 'draft',
            'meta_tags' => $blog_details->meta_tags,
            'meta_description' => $blog_details->meta_description,
            'excerpt' => $blog_details->excerpt,
            'image' => $blog_details->image,
            'user_id' =>Auth::user()->id,
            'author' => $blog_details->author,
            'meta_title' => $blog_details->meta_title,
            'og_meta_title' => $blog_details->og_meta_title,
            'og_meta_description' => $blog_details->og_meta_description,
            'og_meta_image' => $blog_details->og_meta_image,
        ]);

        return redirect()->back()->with([
            'msg' => __('Blog Post cloned success...'),
            'type' => 'success',
        ]);
    }

    public function edit_blog($id)
    {
        $blog_post = Blog::findOrFail($id);
        $all_category = BlogCategory::all();

        return view($this->base_path.'edit')->with([
            'all_category' => $all_category,
            'blog_post' => $blog_post,
        ]);
    }

    public function update_blog(Request $request, $id)
    {   
        
        $request->validate([
            'category' => 'nullable|string',
            'blog_content' => 'nullable|string',
            'tags' => 'nullable|string',
            'title' => 'required',
            'status' => 'required',
            'slug' => 'nullable',
            'meta_tags' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'og_meta_title' => 'nullable|string',
            'og_meta_description' => 'nullable|string',
            'og_meta_image' => 'nullable|string',
            'image' => 'nullable|string|max:191',
        ]);
        Blog::where('id', $id)->update([
            'blog_categories_id' => $request->category,
            'slug' => ! empty($request->slug) ? \Str::slug($request->slug) : \Str::slug($request->title),
            'blog_content' => $request->blog_content,
            'tags' => $request->tags,
            'title' => $request->title,
            'status' => $request->status,
            'meta_tags' => $request->meta_tags,
            'meta_description' => $request->meta_description,
            'excerpt' => $request->excerpt,
            'image' => $request->image,
            'user_id' => Auth::user()->id,
            'author' => Auth::user()->name,
            'meta_title' => $request->meta_title,
            'og_meta_title' => $request->og_meta_title,
            'og_meta_description' => $request->og_meta_description,
            'og_meta_image' => $request->og_meta_image,
        ]);

        return redirect()->back()->with([
            'msg' => __('Item updated...'),
            'type' => 'success',
        ]);
    }

    public function delete_blog(Request $request, $id)
    {
        Blog::findOrFail($id)->delete();

        return redirect()->back()->with([
            'msg' => __('Delete Success...'),
            'type' => 'danger',
        ]);
    }

    public function category()
    {
        $all_category = BlogCategory::all();

        return view($this->base_path.'category')->with([
            'all_category' => $all_category,
        ]);
    }

    public function new_category(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:blog_categories',
            'status' => 'required|string|max:191',
        ]);

        BlogCategory::create($request->all());

        return redirect()->back()->with([
            'msg' => __('New Category Added...'),
            'type' => 'success',
        ]);
    }

    public function update_category(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'status' => 'required|string|max:191',
        ]);

        BlogCategory::findOrFail($request->id)->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with([
            'msg' => __('Category Update Success...'),
            'type' => 'success',
        ]);
    }

    public function delete_category(Request $request, $id)
    {
        if (Blog::where('blog_categories_id', $id)->first()) {
            return redirect()->back()->with([
                'msg' => __('You Can Not Delete This Category, It Already Associated With A Post...'),
                'type' => 'danger',
            ]);
        }
        BlogCategory::findOrFail($id)->delete();

        return redirect()->back()->with([
            'msg' => __('Category Delete Success...'),
            'type' => 'danger',
        ]);
    }

    public function blog_page_settings()
    {
        $all_languages = Language::orderBy('default', 'desc')->get();

        return view($this->base_path.'page-settings.blog')->with(['all_languages' => $all_languages]);
    }

    public function blog_single_page_settings()
    {
        $all_languages = Language::orderBy('default', 'desc')->get();

        return view($this->base_path.'page-settings.blog-single')->with(['all_languages' => $all_languages]);
    }

    public function update_blog_single_page_settings(Request $request)
    {
        $request->validate([
            'blog_single_page_recent_post_item' => 'nullable|string|max:191',
        ]);

        $request->validate([
            'blog_single_page_related_post_title' => 'nullable|string',
            'blog_single_page_share_title' => 'nullable|string',
            'blog_single_page_category_title' => 'nullable|string',
            'blog_single_page_recent_post_title' => 'nullable|string',
            'blog_single_page_tags_title' => 'nullable|string',
        ]);

        $related_post_title = 'blog_single_page_related_post_title';
        $share_title = 'blog_single_page_share_title';
        $category_title = 'blog_single_page_category_title';
        $recent_post_title = 'blog_single_page_recent_post_title';
        $tags_title = 'blog_single_page_tags_title';

        update_static_option($related_post_title, $request->$related_post_title);
        update_static_option($share_title, $request->$share_title);
        update_static_option($category_title, $request->$category_title);
        update_static_option($recent_post_title, $request->$recent_post_title);
        update_static_option($tags_title, $request->$tags_title);

        update_static_option('blog_single_page_recent_post_item', $request->blog_single_page_recent_post_item);

        return redirect()->back()->with([
            'msg' => __('Settings Update Success...'),
            'type' => 'success',
        ]);
    }

    public function update_blog_page_settings(Request $request)
    {

        $request->validate([
            'blog_page_recent_post_widget_items' => 'nullable|string|max:191',
            'blog_page_item' => 'nullable|string|max:191',
        ]);

        $request->validate([
            'blog_page_read_more_btn_text' => 'nullable|string',
        ]);
        $read_more_btn_text = 'blog_page_read_more_btn_text';
        update_static_option($read_more_btn_text, $request->$read_more_btn_text);

        update_static_option('blog_page_item', $request->blog_page_item);
        update_static_option('blog_page_recent_post_widget_items', $request->blog_page_recent_post_widget_items);

        return redirect()->back()->with([
            'msg' => __('Settings Update Success...'),
            'type' => 'success',
        ]);
    }

    public function bulk_action(Request $request)
    {
        $all = Blog::findOrFail($request->ids);
        foreach ($all as $item) {
            $item->delete();
        }

        return response()->json(['status' => 'ok']);
    }

    public function category_bulk_action(Request $request)
    {
        $all = BlogCategory::findOrFail($request->ids);
        foreach ($all as $item) {
            $item->delete();
        }

        return response()->json(['status' => 'ok']);
    }
}
