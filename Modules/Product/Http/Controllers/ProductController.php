<?php

namespace Modules\Product\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Status;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Attributes\Entities\Brand;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\ChildCategory;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\DeliveryOption;
use Modules\Attributes\Entities\Size;
use Modules\Attributes\Entities\SubCategory;
use Modules\Attributes\Entities\Tag;
use Modules\Attributes\Entities\Unit;
use Modules\Badge\Entities\Badge;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductColor;
use Modules\Product\Entities\ProductGallery;
use Modules\Product\Entities\ProductSize;
use Modules\Product\Http\Requests\ProductStoreRequest;
use Modules\Product\Http\Services\Admin\AdminProductServices;
use Modules\TaxModule\Entities\TaxClass;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     * @throws Exception
     */
    public function index(Request $request): Renderable
    {
        $products = AdminProductServices::productSearch($request, "admin");
        $statuses = Status::all();
        $categories = Category::where(['status_id' => 1])->orderBy('sort_order', 'asc')->get();

        return view('product::index', compact("products", "statuses", "categories"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(): Renderable
    {
        $data = $this->productData();

        return view('product::create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     * @param ProductStoreRequest $request
     * @return JsonResponse
     */

    public function store(ProductStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        return response()->json((new AdminProductServices)->store($data) ? ["success" => true, "type" => "success"] : ["success" => false, "type" => "danger"]);
    }

    public function updateImage(Request $request)
    {
        $data = $request->validate([
            "image_id" => "nullable",
            "product_gallery" => "nullable",
            "product_id" => "required|exists:products,id"
        ]);

        // find product
        Product::findOrFail($data['product_id'])->update([
            'image_id' => $data['image_id']
        ]);

        // update those value in product table
        if (!empty(($data["product_gallery"] ?? []) ?? ($data->product_gallery ?? []))) {
            ProductGallery::where("product_id", $data['product_id'])->delete();

            ProductGallery::insert((new AdminProductServices)->prepareProductGalleryData($data, $data['product_id']));
        }

        return response()->json([
            "type" => "success",
            "msg" => __("Successfully updated product image")
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('product::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(int $id): Renderable
    {
        $data = $this->productData();

        $product = (new AdminProductServices)->get_edit_product($id, "single");

        $subCat = $product?->subCategory?->id ?? null;
        $cat = $product?->category?->id ?? null;

        $sub_categories = SubCategory::select("id", "name")->where("category_id", $cat)->where("status_id", 1)->get();
        $child_categories = ChildCategory::select("id", "name")->where("sub_category_id", $subCat)->where("status_id", 1)->get();

        return view('product::edit', compact("data", "product", "sub_categories", "child_categories"));
    }

    /**
     * Update the specified resource in storage.
     * @param ProductStoreRequest $request
     * @param int $id
     */
    public function update(ProductStoreRequest $request, $id)
    {
        $data = $request->validated();
        
        return response()->json(
            (new AdminProductServices)->update($data, $id)
                ? ["success" => true, "type" => "success"]
                : ["success" => false, "type" => "danger"]
        );
    }

    public function clone($id): RedirectResponse
    {
        return (new AdminProductServices)->clone($id) ? back()->with(FlashMsg::clone_succeed('Product')) : back()->with(FlashMsg::clone_failed('Product'));
    }

    private function validateUpdateStatus($req): array
    {
        return Validator::make($req, [
            "id" => "required",
            "status_id" => "required",
        ])->validated();
    }

    public function update_status(Request $request): JsonResponse
    {
        $data = $this->validateUpdateStatus($request->all());

        return (new AdminProductServices)->updateStatus($data["id"], $data["status_id"]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return bool|null
     */
    public function destroy($id)
    {
        return (new AdminProductServices)->delete($id);
    }

    public function bulk_destroy(Request $request): JsonResponse
    {
        return response()->json((new AdminProductServices)->bulk_delete_action($request->ids) ? ["success" => true, "type" => "success"] : ["success" => false, "type" => "danger"]);
    }

    public function trash(): Renderable
    {
        $products = Product::with('category', 'subCategory', 'childCategory', 'brand', 'inventory')->onlyTrashed()->get();
        return view('product::trash', compact("products"));
    }

    public function restore($id)
    {
        $restore = Product::onlyTrashed()->findOrFail($id)->restore();
        return back()->with($restore ? FlashMsg::restore_succeed('Trashed Product') : FlashMsg::restore_failed('Trashed Product'));
    }

    public function trash_delete($id)
    {
        return (new AdminProductServices)->trash_delete($id) ? back()->with(FlashMsg::delete_succeed('Trashed Product')) : back()->with(FlashMsg::delete_failed('Trashed Product'));
    }

    public function trash_bulk_destroy(Request $request)
    {
        return response()->json((new AdminProductServices)->trash_bulk_delete_action($request->ids) ? ["success" => true, "type" => "success"] : ["success" => false, "type" => "danger"]);
    }

    public function trash_empty(Request $request)
    {
        $ids = explode('|', $request->ids);
        return response()->json((new AdminProductServices)->trash_bulk_delete_action($ids) ? ["success" => true, "type" => "success"] : ["success" => false, "type" => "danger"]);
    }

    public function productSearch(Request $request): string
    {
        $statuses = Status::all();

        // Validate the request data
        $data = Validator::make($request->all(), [
            'name' => 'nullable|string|max:100',
            'sku' => 'nullable|string|max:100',
            'sort_order' => 'nullable|string|max:50',
            'category' => 'nullable|string',
            'subcategory' => 'nullable|string',
            'childcategory' => 'nullable|string',
            'from_price' => 'nullable|numeric|min:0',
            'to_price' => 'nullable|numeric|min:0',
        ])->validated();

        $productsQuery = Product::query()->with(['category', 'subCategory', 'childCategory']);

        // Apply filters if provided
        if (!empty($data['name'])) {
            $productsQuery->where('name', 'like', '%' . $data['name'] . '%');
        }

        if (!empty($data['sku'])) {
            $productsQuery->whereHas('inventory', function ($query) use ($data) {
                $query->where('product_inventories.sku', 'like', '%' . $data['sku'] . '%');
            });
        }

        if (!empty($data['category'])) {
            $productsQuery->whereHas('category', function ($query) use ($data) {
                $query->where('categories.slug', $data['category']);  // Matching by 'slug' instead of 'id'
            });
        }

        if (!empty($data['subcategory'])) {
            $productsQuery->whereHas('subCategory', function ($query) use ($data) {
                $query->where('sub_categories.id', $data['subcategory']);
            });
        }

        if (!empty($data['childcategory'])) {
            $productsQuery->whereHas('childCategory', function ($query) use ($data) {
                $query->where('child_categories.id', $data['childcategory']);
            });
        }

        if (!empty($data['from_price'])) {
            $productsQuery->where('price', '>=', $data['from_price']);
        }

        if (!empty($data['to_price'])) {
            $productsQuery->where('price', '<=', $data['to_price']);
        }

        // Apply sorting based on sort_order values
        if (!empty($data['sort_order'])) {
            switch ($data['sort_order']) {
                case 1:
                    // Sort by price low to high
                    $productsQuery->orderBy('price', 'asc');
                    break;
                case 2:
                    // Sort by price high to low
                    $productsQuery->orderBy('price', 'desc');
                    break;
                case 3:
                    // Sort by name A-Z
                    $productsQuery->orderBy('name', 'asc');
                    break;
                case 4:
                    // Sort by name Z-A
                    $productsQuery->orderBy('name', 'desc');
                    break;
                default:
                    // Optional: default sorting if necessary
                    $productsQuery->orderBy('created_at', 'desc'); // Example default sorting
            }
        }


        // Paginate the results
        $perPage = 10; // Set the number of items per page
        $products = $productsQuery->paginate($perPage); // Fetch paginated results

        // Prepare the data for the view
        return view('product::search', [
            'products' => [
                'items' => $products->items(),
                'per_page' => $products->perPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'total_page' => $products->lastPage(),
                'total_items' => $products->total(),
                'current_page' => $products->currentPage(),
                'links' => $products->appends($request->all())->links() // Get the HTML links directly
            ],
            'statuses' => $statuses
        ])->render();
    }








    public function productData(): array
    {
        return [
            "brands" => Brand::select("id", "name")->get(),
            "badges" => Badge::where("status", "active")->get(),
            "units" => Unit::select("id", "name")->get(),
            "tags" => Tag::select("id", "tag_text as name")->get(),
            "categories" => Category::select("id", "name")->get(),
            "deliveryOptions" => DeliveryOption::select("id", "title", "sub_title", "icon")->get(),
            "all_attribute" => ProductAttribute::all()->groupBy('title')->map(fn($query) => $query[0]),
            "product_colors" => Color::all(),
            "product_sizes" => Size::all(),
            "tax_classes" => TaxClass::all(),
        ];
    }
}
