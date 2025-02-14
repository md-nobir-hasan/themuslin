<?php

namespace Modules\Vendor\Http\Controllers\Api;

use App\MediaUpload;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class VendorMediaUploadApiController extends Controller
{
    public function upload_media_file(Request $request)
    {
        $request->validate([
            'file' => 'nullable|mimes:jpg,jpeg,png,gif|max:11000'
        ]);

        if ($request->hasFile('file')) {
            $image = $request->file;
            $image_dimension = getimagesize($image);
            $image_width = $image_dimension[0];
            $image_height = $image_dimension[1];
            $image_dimension_for_db = $image_width . ' x ' . $image_height . ' pixels';
            $image_size_for_db = $image->getSize();

            $image_extension = $image->getClientOriginalExtension();
            $image_name_with_ext = $image->getClientOriginalName();

            $image_name = pathinfo($image_name_with_ext, PATHINFO_FILENAME);
            $image_name = strtolower(Str::slug($image_name));

            $image_db = $image_name . time() . '.' . $image_extension;
            $image_grid = 'grid-' . $image_db;
            $image_large = 'large-' . $image_db;
            $image_thumb = 'thumb-' . $image_db;
            $image_p_grid = 'product-' . $image_db;

            $folder_path = 'assets/uploads/media-uploader/';

            $resize_large_image = Image::make($image)->resize(740, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $resize_grid_image = Image::make($image)->resize(350, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $resize_p_grid_image = Image::make($image)->resize(230, 270);
            $resize_thumb_image = Image::make($image)->resize(150, 150);

            $request->file->move($folder_path, $image_db);

            $media = MediaUpload::create([
                'title' => $image_name_with_ext,
                'size' => formatBytes($image_size_for_db),
                'path' => $image_db,
                'dimensions' => $image_dimension_for_db,
                'user_id' => null,
                'vendor_id' => \Auth::guard('sanctum')->user()->id
            ]);

            if ($image_width > 150) {
                $resize_thumb_image->save($folder_path . $image_thumb);
                $resize_grid_image->save($folder_path . $image_grid);
                $resize_large_image->save($folder_path . $image_large);
                $resize_p_grid_image->save($folder_path . $image_p_grid);
            }

            return $media;
        }
    }

    public function all_upload_media_file(Request $request)
    {
        $all_images = MediaUpload::where(['vendor_id' => auth('sanctum')->id()])
            ->orderBy('id', 'DESC')->get();
        $all_image_files = [];

        foreach ($all_images as $image) {
            if (file_exists('assets/uploads/media-uploader/' . $image->path)) {
                $image_url = asset('assets/uploads/media-uploader/' . $image->path);
                $all_image_files[] =  [
                    'image_id' => $image->id,
                    'title' => $image->title,
                    'dimensions' => $image->dimensions,
                    'alt' => $image->alt,
                    'size' => $image->size,
                    'path' => $image->path,
                    'img_url' => $image_url,
                    'upload_at' => date_format($image->created_at, 'd M y')
                ];
            }
        }

        return response()->json($all_image_files);
    }

    public function delete_upload_media_file(Request $request)
    {
        $get_image_details = MediaUpload::where(['id' => $request->img_id,'vendor_id' => auth()->guard('sanctum')->id()])->first();

        if(empty($get_image_details)) {
            return response()->json([
                "msg" => __("No image found"),
                "code" => 404
            ],404);
        }

        if (file_exists('assets/uploads/media-uploader/' . $get_image_details->path)) {
            unlink('assets/uploads/media-uploader/' . $get_image_details->path);
        }
        if (file_exists('assets/uploads/media-uploader/grid-' . $get_image_details->path)) {
            unlink('assets/uploads/media-uploader/grid-' . $get_image_details->path);
        }
        if (file_exists('assets/uploads/media-uploader/large-' . $get_image_details->path)) {
            unlink('assets/uploads/media-uploader/large-' . $get_image_details->path);
        }
        if (file_exists('assets/uploads/media-uploader/thumb-' . $get_image_details->path)) {
            unlink('assets/uploads/media-uploader/thumb-' . $get_image_details->path);
        }

        $get_image_details->delete();

        return response()->json([
            "msg" => __("Successfully image deleted")
        ]);
    }

    public function get_image_for_loadmore(Request $request){
        $all_images = MediaUpload::where(['vendor_id' => auth('sanctum')->id()])
            ->orderBy('id', 'DESC')
            ->skip($request->skip)
            ->take(20)
            ->get();

        $all_image_files = [];

        foreach ($all_images as $image){
            if (file_exists('assets/uploads/media-uploader/'.$image->path)){
                $image_url = asset('assets/uploads/media-uploader/'.$image->path);
                if (file_exists('assets/uploads/media-uploader/grid-' . $image->path)) {
                    $image_url = asset('assets/uploads/media-uploader/grid-' . $image->path);
                }
                $all_image_files[] = [
                    'image_id' => $image->id,
                    'title' => $image->title,
                    'dimensions' => $image->dimensions,
                    'alt' => $image->alt,
                    'size' => $image->size,
                    'path' => $image->path,
                    'img_url' => $image_url,
                    'upload_at' => date_format($image->created_at, 'd M y')
                ];

            }
        }

        return response()->json($all_image_files);
    }

    public function alt_change_upload_media_file(Request $request)
    {
        $request->validate([
            'imgid' => 'required',
            'alt' => 'nullable',
        ]);

        $update = MediaUpload::where(['id' => $request->imgid,'vendor_id' => auth('sanctum')->id()])
            ->update(['alt' => $request->alt]);

        return [
            "msg"=> $update ? __('alt update done') : __("Failed to update this item"),
            "success" => (bool)$update
        ];
    }
}
