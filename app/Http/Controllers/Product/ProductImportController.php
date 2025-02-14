<?php

namespace Modules\CountryManage\Http\Controllers\Product;

use App\Helpers\FlashMsg;
use App\MediaUpload;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductSubCategory;

class ProductImportController extends Controller
{
    public function import_settings(Request $request)
    {
        return view('backend.products.product-import');
    }

    public function update_import_settings(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt|max:150000']);

        // work on file mapping
        if ($request->hasFile('csv_file')) {
            $file = $request->csv_file;
            $extension = $file->getClientOriginalExtension();

            //copy file to temp folder
            if ($extension == 'csv') {
                $old_file = Session::get('import_csv_file_name');
                if (file_exists('assets/uploads/import/'.$old_file)) {
                    @unlink('assets/uploads/import/'.$old_file);
                }
                $file_name_with_ext = $file->getClientOriginalName();

                $file_name = pathinfo($file_name_with_ext, PATHINFO_FILENAME);
                $file_name = strtolower(Str::slug($file_name));

                $file_tmp_name = $file_name.time().'.'.$extension;
                $file->move('assets/uploads/import', $file_tmp_name);

                $data = array_map('str_getcsv', file('assets/uploads/import/'.$file_tmp_name));
                $csv_data = array_slice($data, 0, 1);

                Session::put('import_csv_file_name', $file_tmp_name);
                $category = ProductCategory::where(['status' => 'publish'])->get();
                $sub_category = ProductSubCategory::where(['status' => 'publish'])->get();

                return view('backend.products.product-import', [
                    'import_data' => $csv_data,
                    'sub_category' => $sub_category,
                    'category' => $category,
                ]);
            }
        }

        return back()->with(['msg' => __('something went wrong try again!'), 'type' => 'danger']);
    }

    public function import_to_database_settings(Request $request)
    {
        $file_tmp_name = Session::get('import_csv_file_name');
        $data = array_map('str_getcsv', file('assets/uploads/import/'.$file_tmp_name));
        $csv_data = current(array_slice($data, 0, 1));
        $csv_data = array_map(function ($item) {
            return trim($item);
        }, $csv_data);
        $imported_products = 0;
        $title = array_search($request->title, $csv_data, true);
        $short_description = array_search($request->short_description, $csv_data, true);
        $description = array_search($request->description, $csv_data, true);
        $badge = array_search($request->badge, $csv_data, true);
        $slug = array_search($request->slug, $csv_data, true);
        $sku = array_search($request->sku, $csv_data, true);
        $stock = array_search($request->stock, $csv_data, true);
        $sales = array_search($request->sales, $csv_data, true);
        $regular_price = array_search($request->regular_price, $csv_data, true);
        $sale_price = array_search($request->sale_price, $csv_data, true);
        $image = array_search($request->image, $csv_data, true);

        foreach ($data as $index => $item) {
            if ($index === 0 || empty($item[$title])) {
                continue;
            }

            $product_data = [
                'title' => $item[$title] ?? '',
                'slug' => $item[$slug] ? Str::slug($item[$slug]) : Str::slug($item[$title] ?? Str::random(16)),
                'summary' => $item[$short_description] ?? '',
                'description' => $item[$description] ?? '',
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id ?? null,
                'price' => $item[$regular_price] ?? '',
                'sale_price' => $item[$sale_price] ?? 0,
                'badge' => $item[$badge] ?? '',
                'status' => $request->status,
            ];

            if ($sales) {
                $product_data['sold_count'] = is_int($item[$sales]) ? $item[$sales] : 0;
            }

            if ($image) {
                $mage_id = $this->import_media_image($item[$image]);
                $product_data['image'] = $mage_id;
            }

            try {
                DB::beginTransaction();
                $product = Product::create($product_data);
                $imported_products++;

                $inventory = ProductInventory::create([
                    'product_id' => $product->id,
                    'sku' => $item[$sku] ?? '',
                    'stock_count' => $item[$stock] ?? '',
                    'sold_count' => 0,
                ]);

                DB::commit();

                return back()->with(FlashMsg::settings_update($imported_products.' '.__('Products imported successfully')));
            } catch (\Throwable $th) {
                DB::rollBack();

                return back()->with(FlashMsg::settings_update(__('Products import failed')));
            }
        }

        return back()->with(FlashMsg::settings_update($imported_products.' '.__('products imported successfully')));
    }

    protected function import_media_image($image)
    {
        if (empty($image)) {
            return;
        }

        $image_file = file_get_contents($image);
        $image_name_with_ext = basename($image);
        $image_name = pathinfo($image, PATHINFO_FILENAME);
        $image_extension = pathinfo($image, PATHINFO_EXTENSION);
        $image_db = $image_name.time().'.'.$image_extension;

        file_put_contents('assets/uploads/media-uploader/'.$image_db, $image_file);

        $uploaded_path = 'assets/uploads/media-uploader/'.$image_db;
        $image_size = getimagesize($uploaded_path);
        $image_width = $image_size[0] ?? '';
        $image_height = $image_size[1] ?? '';
        $image_dimension_for_db = $image_width.'x'.$image_height;

        $image_grid = 'grid-'.$image_db;
        $image_large = 'large-'.$image_db;
        $image_thumb = 'thumb-'.$image_db;

        $folder_path = 'assets/uploads/media-uploader/';
        $resize_grid_image = Image::make($uploaded_path)->resize(350, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $resize_large_image = Image::make($uploaded_path)->resize(740, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $resize_thumb_image = Image::make($uploaded_path)->resize(150, 150);

        $image_info = MediaUpload::create([
            'title' => $image_name_with_ext,
            'size' => ! empty($image_size['bits']) ? formatBytes($image_size['bits']) : null,
            'path' => $image_db,
            'dimensions' => $image_dimension_for_db,
        ]);

        if ($image_width > 150) {
            $resize_thumb_image->save($folder_path.$image_thumb);
            $resize_grid_image->save($folder_path.$image_grid);
            $resize_large_image->save($folder_path.$image_large);
        }

        return $image_info->id ?? false;
    }
}
