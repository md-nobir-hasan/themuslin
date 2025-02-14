<?php

namespace App\Http\Controllers\Admin;

use App\Http\Services\Media\MediaHelper;
use App\MediaUpload;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Routing\Controller;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class MediaUploadController extends Controller
{
    /**
     * @throws UploadFailedException|UploadMissingFileException
     */
    public function upload_media_file(Request $request)
    {
        // create the file receiver
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        // receive the file
        $save = $receiver->receive();

        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
            MediaHelper::insert_media_image($save->getFile(), type: 'web');
            // save the file and return any response you need, current example uses `move` function. If you are
            // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
        }
    }

    public function all_upload_media_file(Request $request)
    {
        $all_images = MediaUpload::when(auth('web')->check(), function ($query){
            $query->where("user_id", auth('web')->user()->id);
        })->when(auth('vendor')->check(), function ($query){
            $query->where("vendor_id", auth('vendor')->user()->id);
        })->when(auth('admin')->check(), function ($query){
            $query->whereNull("vendor_id")->whereNull("user_id");
        })->orderBy('id', 'DESC')
        ->take(20)->get();

        $selected_image = MediaUpload::when(auth('web')->check(), function ($query){
            $query->where("user_id", auth('web')->user()->id);
        })->when(auth('vendor')->check(), function ($query){
            $query->where("vendor_id", auth('vendor')->user()->id);
        })->when(auth('admin')->check(), function ($query){
            $query->whereNull("vendor_id")->whereNull("user_id");
        })->find($request->selected);


        $all_image_files = [];
        if (!empty($selected_image)){
            if (file_exists('assets/uploads/media-uploader/'.$selected_image->path)) {

                $image_url = asset('assets/uploads/media-uploader/' . $selected_image->path);
                // if (file_exists('assets/uploads/media-uploader/grid-' . $selected_image->path)) {
                //     $image_url = asset('assets/uploads/media-uploader/grid-' . $selected_image->path);
                // }

                $all_image_files[] = [
                    'image_id' => $selected_image->id,
                    'title' => $selected_image->title,
                    'dimensions' => $selected_image->dimensions,
                    'alt' => $selected_image->alt,
                    'size' => $selected_image->size,
                    'path' => $selected_image->path,
                    'img_url' => $image_url,
                    'upload_at' => date_format($selected_image->created_at, 'd M y')
                ];
            }
        }

        foreach ($all_images as $image){
            if (file_exists('assets/uploads/media-uploader/'.$image->path)){
                $image_url = asset('assets/uploads/media-uploader/'.$image->path);
                // if (file_exists('assets/uploads/media-uploader/grid-' . $image->path)) {
                //     $image_url = asset('assets/uploads/media-uploader/grid-' . $image->path);
                // }

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

    public function delete_upload_media_file(Request $request)
    {
        $get_image_details = MediaUpload::find($request->img_id);
        if (file_exists('assets/uploads/media-uploader/'.$get_image_details->path)){
            unlink('assets/uploads/media-uploader/'.$get_image_details->path);
        }
        // if (file_exists('assets/uploads/media-uploader/grid-'.$get_image_details->path)){
        //     unlink('assets/uploads/media-uploader/grid-'.$get_image_details->path);
        // }
        // if (file_exists('assets/uploads/media-uploader/large-'.$get_image_details->path)){
        //     unlink('assets/uploads/media-uploader/large-'.$get_image_details->path);
        // }
        // if (file_exists('assets/uploads/media-uploader/thumb-'.$get_image_details->path)){
        //     unlink('assets/uploads/media-uploader/thumb-'.$get_image_details->path);
        // }
        // if (file_exists('assets/uploads/media-uploader/semi-'.$get_image_details->path)){
        //     unlink('assets/uploads/media-uploader/semi-'.$get_image_details->path);
        // }
        MediaUpload::when(auth('web')->check(), function ($query){
            $query->where("user_id", auth('web')->user()->id);
        })->when(auth('vendor')->check(), function ($query){
            $query->where("vendor_id", auth('vendor')->user()->id);
        })->when(auth('admin')->check(), function ($query){
            $query->whereNull("vendor_id");
            $query->whereNull("user_id");
        })->find($request->img_id)->delete();

        return redirect()->back();
    }

    public function regenerate_media_images(){
        
        $all_media_file = MediaUpload::when(auth('web')->check(), function ($query){
            $query->where("user_id", auth('web')->user()->id);
        })->when(auth('vendor')->check(), function ($query){
            $query->where("vendor_id", auth('vendor')->user()->id);
        })->when(auth('admin')->check(), function ($query){
            $query->whereNull("vendor_id");
            $query->whereNull("user_id");
        })->all();

        // foreach ($all_media_file as $img){

        //     if (!file_exists('assets/uploads/media-uploader/'.$img->path)){
        //         continue;
        //     }
        //     $image = 'assets/uploads/media-uploader/'. $img->path;
        //     $image_dimension = getimagesize($image);
        //     $image_width = $image_dimension[0];
        //     $image_height = $image_dimension[1];

        //     $image_db = $img->path;
        //     $image_grid = 'grid-'.$image_db ;
        //     $image_large = 'large-'. $image_db;
        //     $image_thumb = 'thumb-'. $image_db;

        //     $folder_path = 'assets/uploads/media-uploader/';
        //     $resize_grid_image = Image::make($image)->resize(350, null,function ($constraint) {
        //         $constraint->aspectRatio();
        //     });
        //     $resize_large_image = Image::make($image)->resize(740, null,function ($constraint) {
        //         $constraint->aspectRatio();
        //     });
        //     $resize_thumb_image = Image::make($image)->resize(150, 150);

        //     if ($image_width > 150){
        //         $resize_thumb_image->save($folder_path . $image_thumb);
        //         $resize_grid_image->save($folder_path . $image_grid);
        //         $resize_large_image->save($folder_path . $image_large);
        //     }

        // }
        return 'regenerate done';
    }

    public function alt_change_upload_media_file(Request $request){
        $request->validate([
            'imgid' => 'required',
            'alt' => 'nullable',
        ]);

        MediaUpload::when(auth('web')->check(), function ($query){
            $query->where("user_id", auth('web')->user()->id);
        })->when(auth('vendor')->check(), function ($query){
            $query->where("vendor_id", auth('vendor')->user()->id);
        })->when(auth('admin')->check(), function ($query){
            $query->whereNull("vendor_id");
            $query->whereNull("user_id");
        })->where('id',$request->imgid)->update(['alt' => $request->alt]);
        return 'alt update done';
    }

    public function all_upload_media_images_for_page(){
        $all_media_images = MediaUpload::when(auth('web')->check(), function ($query){
            $query->where("user_id", auth('web')->user()->id);
        })->when(auth('vendor')->check(), function ($query){
            $query->where("vendor_id", auth('vendor')->user()->id);
        })->when(auth('admin')->check(), function ($query){
            $query->whereNull("vendor_id");
            $query->whereNull("user_id");
        })->orderBy('id','desc')->get();

        return view('backend.media-images.media-images')->with(['all_media_images' => $all_media_images]);
    }

    public function get_image_for_loadmore(Request $request){
        $all_images = MediaUpload::when(auth('web')->check(), function ($query){
            $query->where("user_id", auth('web')->user()->id);
        })->when(auth('vendor')->check(), function ($query){
            $query->where("vendor_id", auth('vendor')->user()->id);
        })->when(auth('admin')->check(), function ($query){
            $query->whereNull("vendor_id");
            $query->whereNull("user_id");
        })->orderBy('id', 'DESC')->skip($request->skip)->take(20)->get();

        $all_image_files = [];
        foreach ($all_images as $image){
            if (file_exists('assets/uploads/media-uploader/'.$image->path)){
                $image_url = asset('assets/uploads/media-uploader/'.$image->path);
                // if (file_exists('assets/uploads/media-uploader/grid-' . $image->path)) {
                //     $image_url = asset('assets/uploads/media-uploader/grid-' . $image->path);
                // }
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
}
