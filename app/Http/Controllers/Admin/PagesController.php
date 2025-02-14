<?php

namespace App\Http\Controllers\Admin;

use App\Page;
use App\StaticOption;
use App\MediaUpload;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PagesController extends Controller
{
    public function index()
    {
        $all_pages = Page::all();

        $dynamic_page_ids = StaticOption::select('option_name', 'option_value')->whereIn('option_name', [
            'home_page',
            'product_page',
            'blog_page',
        ])->get()->pluck('option_name', 'option_value');

        return view('backend.pages.page.index')->with([
            'dynamic_page_ids' => $dynamic_page_ids,
            'all_pages' => $all_pages,
        ]);
    }

    public function new_page()
    {
        return view('backend.pages.page.new');
    }

    public function store_new_page(Request $request)
    {
        $request->validate( [
            'content' => 'nullable',
            'meta_tags' => 'nullable',
            'meta_description' => 'nullable',
            'title' => 'required',
            'slug' => 'nullable',
            'visibility' => 'nullable',
            'status' => 'required|string|max:191',
            "navbar_variant" => 'nullable|string|max:191',
            "breadcrumb_status" => 'nullable|string|max:191',
            'page_container_option' => 'nullable|string|max:191',
            'navbar_category_dropdown_open' => 'nullable|string|max:191',
        ]);

        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
        $slug_check = Page::where(['slug' => $slug])->count();
        $slug = $slug_check > 0 ? $slug . '2' : $slug;

        Page::create([
            'title' => $request->title,
            'slug' => $slug,
            'meta_tags' => $request->meta_tags,
            'meta_description' => $request->meta_description,
            'content' => $request->page_content,
            'status' => $request->status,
            'visibility' => $request->visibility,
            'page_builder_status' => (boolean) $request->page_builder_status,
            'page_container_option' => (int) !! $request->page_container_option,
            'navbar_variant' => $request->navbar_variant ?? 0,
            'navbar_category_dropdown_open' => (int) !! $request->navbar_category_dropdown_open,
            'breadcrumb_status' => (int) !! $request->breadcrumb_status,
        ]);

        return redirect()->back()->with([
            'msg' => __('New Page Created...'),
            'type' => 'success'
        ]);
    }

    public function edit_page($id)
    {
        $page_post = Page::find($id);
        return view('backend.pages.page.edit')->with([
            'page_post' => $page_post,
        ]);
    }

    public function update_page(Request $request, $id)
    {
        $request->validate([
            'content' => 'nullable',
            'meta_tags' => 'nullable',
            'meta_description' => 'nullable',
            'title' => 'required',
            'slug' => 'nullable',
            'visibility' => 'nullable',
            'status' => 'required|string|max:191',
            "navbar_variant" => 'required|string|max:191',
            "breadcrumb_status" => 'nullable|string|max:191',
            'page_container_option' => 'nullable|string|max:191',
            'navbar_category_dropdown_open' => 'nullable|string|max:191',
            'megamenu' => 'required_if:navbar_variant,==,1'
        ]);

        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
        $slug_check = Page::where(['slug' => $slug])->count();
        $slug = $slug_check > 1 ? $slug . '2' : $slug;

        if($request->navbar_variant == 1){
            update_static_option("megamenu",$request->megamenu);
        }

        Page::where('id', $id)->update([
            'title' => $request->title,
            'slug' => $slug,
            'meta_tags' => $request->meta_tags,
            'meta_description' => $request->meta_description,
            'content' => $request->page_content,
            'status' => $request->status,
            'visibility' => $request->visibility,
            'page_builder_status' => (boolean) $request->page_builder_status,
            'navbar_variant' => $request->navbar_variant,
            'breadcrumb_status' => $request->breadcrumb_status ? 1 : 0,
            'page_container_option' => (int) !! $request->page_container_option,
            'navbar_category_dropdown_open' => (int) !! $request->navbar_category_dropdown_open,
        ]);

        return redirect()->back()->with([
            'msg' => __('Page updated...'),
            'type' => 'success'
        ]);
    }

    public function pageImage($id) {

        $page_post = Page::find($id);
        return view('backend.pages.page.image')->with([
            'page_post' => $page_post,
        ]);
    }


    public function uploadPageImage(Request $request, $id)
    {

        $file_ext_limit = ['jpg', 'png', 'webp', 'pdf'];
        $file_weight_limit = '2';

        if(!empty($request->Images)) {

            foreach ($request->Images as $key => $file) {

                // Signle file validation & upload process

                $extension = $file->extension();
                $size = $file->getSize();

                $path = 'assets/uploads/media-uploader/pages/';

                if(in_array($extension, $file_ext_limit)) {

                    $image_name_with_ext = $file->getClientOriginalName();
                    $image_name = pathinfo($image_name_with_ext, PATHINFO_FILENAME);
                    $image_name = strtolower(Str::slug($image_name));
                    $image_db = $image_name . rand() . '.' . $extension;

                    $file->move($path, $image_db);

                    list($width, $height, $type, $attr) = getimagesize($path . $image_db);

                    $data = [
                        'identifier_type' => 'Page',
                        'identifier_id' => $id,
                        'title' => $image_name_with_ext ,
                        'path' => $image_db,
                        'title_text' => $request->title_text,
                        'device' => $request->device,
                        'dimensions' =>  $width . 'x' . $height . ' pixels',
                    ];

                    if(($size = $size / 1024) < 1024) {
                        $data['size'] = number_format($size) . ' KB';
                    }
                    else if(($size = $size / 1024) < 1024) {
                        $data['size'] = number_format($size, 2) . ' MB';
                    }

                    $saved_image = MediaUpload::create($data);
                }
            }
        }

        return redirect()->back()->with([
            'msg' => __('Image uploaded successfully'),
            'type' => 'success'
        ]);
    }

    public function editPageImage(Request $request, $id)
    {
        MediaUpload::find($id);

        MediaUpload::where('id', $id)->update([
            'title_text' => $request->title_text,
            'device' => $request->device,
            'sort_order' => $request->sort_order,
        ]);

        return redirect()->back()->with([
            'msg' => __('Image data updated'),
            'type' => 'success'
        ]);
    }

    public function deletePageImage($id)
    {
        MediaUpload::find($id)->delete();
        return redirect()->back()->with([
            'msg' => __('Image Delete'),
            'type' => 'success'
        ]);
    }




    public function delete_page(Request $request, $id)
    {
        Page::find($id)->delete();
        return redirect()->back()->with([
            'msg' => __('Page Delete Success...'),
            'type' => 'danger'
        ]);
    }

    public function bulk_action(Request $request)
    {
        $all = Page::whereIn('id', $request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
