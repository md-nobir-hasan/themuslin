<?php

namespace Modules\Product\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\Size;
use Modules\Product\Entities\InventoryDetailsAttribute;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductColor;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetails;
use Modules\Product\Entities\ProductSize;

use function __;
use function back;
use function response;
use function view;

class ProductInventoryController extends Controller
{
    const BASE_URL = 'product::backend.inventory.';

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-inventory-list|product-inventory-create|product-inventory-edit|product-inventory-delete', ['only', ['index']]);
        $this->middleware('permission:product-inventory-create', ['only', ['create', 'store']]);
        $this->middleware('permission:product-inventory-edit', ['only', ['edit', 'update']]);
        $this->middleware('permission:product-inventory-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_inventory_products = ProductInventory::with('product')->get();

        return view(self::BASE_URL.'all', compact('all_inventory_products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_products = Product::all();
        $all_attributes = ProductAttribute::select('id', 'title', 'terms')->get();

        return view(self::BASE_URL.'new')->with([
            'all_products' => $all_products,
            'all_attributes' => $all_attributes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //  : inventory details sku, stock count and sale count to be removed
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku' => 'required|string|unique:product_inventories,sku',
            'stock_count' => 'nullable|numeric',
            'inventory_details' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $product_inventory = ProductInventory::create([
                'product_id' => $request->sanitize_html('product_id'),
                'sku' => 'SKU-'.$request->sanitize_html('sku'),
                'stock_count' => $request->sanitize_html('stock_count'),
            ]);

            if ($request->inventory_details && count($request->inventory_details)) {
                $this->insertInventoryDetails($product_inventory->id, $request->inventory_details);
            }

            DB::commit();

            return response()->json(FlashMsg::create_succeed(__('Product Inventory')));
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(FlashMsg::create_failed(__('Product Inventory')), 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductInventory $item
     * @return Application|Factory|View
     */
    public function edit(ProductInventory $item)
    {
        $inventory = $item->where('id', $item->id)->with('details')->first();
        $inventory_details = optional($inventory->details);
        $inventory_details_ids = optional($inventory_details)->pluck('id')->toArray();
        $inventory_details_attributes = InventoryDetailsAttribute::whereIn('inventory_details_id', $inventory_details_ids)->get();

        $all_products = Product::all();
        $all_attributes = ProductAttribute::select('id', 'title', 'terms')->get();
        $product_colors = Color::all();
        $product_sizes = Size::all();

        return view(self::BASE_URL.'edit')->with([
            'inventory' => $inventory,
            'inventory_details' => $inventory_details,
            'inventory_details_attributes' => $inventory_details_attributes,
            'all_products' => $all_products,
            'all_attributes' => $all_attributes,
            'product_colors' => $product_colors,
            'product_sizes' => $product_sizes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Product\ProductInventory  $productInventory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_inventories',
            'product_id' => 'required|exists:products,id',
            'sku' => 'required|string',
            'stock_count' => 'required|numeric',

            'inventory_details_id' => 'nullable|array',
            'inventory_details_size' => 'nullable|array',
            'inventory_details_color' => 'nullable|array',
            'inventory_details_additional_price' => 'nullable|array',
            'inventory_details_stock_count' => 'nullable|array',
            'inventory_details_sold_count' => 'nullable|array',
            'inventory_details_image' => 'nullable|array',
        ]);

        try {
            Db::beginTransaction();

            $product_inventory = ProductInventory::find($request->id);
            $total_stock_count = $request->sanitize_html('stock_count') ?? 0;

            // if variant-wise stock count given, ignore the given stock count and insert the sum of variant-wise stock count
            if ($request->get('inventory_details_stock_count') && is_array($request->get('inventory_details_stock_count'))) {
                $total_stock_count = array_sum($request->get('inventory_details_stock_count'));
            }

            $product_inventory->update([
                'product_id' => $request->sanitize_html('product_id'),
                'sku' => $request->sanitize_html('sku'),
                'stock_count' => $total_stock_count,
            ]);

            // update variant-wise stock data
            if (isset($request->inventory_details_id) && is_array($request->inventory_details_id)) {
                foreach ($request->inventory_details_id as $key => $inventory_details_id) {
                    ProductInventoryDetails::where('id', $inventory_details_id)->update([
                        'color' => sanitize_html($request->inventory_details_color[$key]) ?? '',
                        'size' => sanitize_html($request->inventory_details_size[$key]) ?? '',
                        'additional_price' => sanitize_html($request->inventory_details_additional_price[$key]) ?? '',
                        'image' => sanitize_html($request->inventory_details_image[$key]) ?? '',
                        'stock_count' => sanitize_html($request->inventory_details_stock_count[$key]) ?? '',
                        'sold_count' => sanitize_html($request->inventory_details_sold_count[$key]) ?? '',
                    ]);
                }
            }

            DB::commit();

            return back()->with(FlashMsg::update_succeed(__('Product Inventory')));
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->with(FlashMsg::update_failed(__('Product Inventory')), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product\ProductInventory  $productInventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return (bool) ProductInventoryDetails::find($request->id)->delete();
    }

    public function bulk_action(Request $request)
    {
        $deleted = ProductInventory::whereIn('id', $request->ids)->delete();
        if ($deleted) {
            back()->with(FlashMsg::delete_succeed(__('Product Inventory')));
        }

        return back()->with(FlashMsg::delete_failed(__('Product Inventory')));
    }

    public function removeProductInventory(Request $request)
    {
        $product = Product::find($request->id);
        $updated = false;

        if ($product) {
            $all_attributes = json_decode($product->attributes, true);
            $old = $all_attributes;

            //  : check
            if (is_array($all_attributes) && ! empty($all_attributes)) {
                foreach ($all_attributes as $attribute_id => $attributes) {
                    foreach ($attributes as $key => $attribute) {
                        if ($attribute['type'] == $request->type && $attribute['name'] == $request->value) {
                            unset($all_attributes[$attribute_id][$key]);
                        }
                    }

                    if (empty($all_attributes[$attribute_id])) {
                        unset($all_attributes[$attribute_id]);
                    }
                }

                $updated = $product->update([
                    'attributes' => $all_attributes,
                ]);
            }
        }

        return response()->json([
            'status' => $updated ? 'success' : 'error',
            'msg' => $updated ? __('Inventory data removed') : __('Could not remove inventory data'),
        ]);
    }

    public function removeInventoryDetailsAttribute(Request $request)
    {
        $details_attribute = InventoryDetailsAttribute::find($request->id);
        $deleted = $details_attribute ? $details_attribute->delete() : null;

        return FlashMsg::explain(
            'error',
            $deleted ? __('Attribute deleted') : __('Attribute delete failed')
        );
    }

    /** =========================================================================
     *                          HELPER FUNCTIONS
     * ========================================================================= */
    private function insertInventoryDetails($inventory_id, $inventory_details)
    {
        return;
        foreach ($inventory_details as $details) {
            //  === Product_Inventory_Details ===
            ProductInventoryDetails::create([
                'inventory_id',
                'product_id',
                'color',
                'size',
                'selected_attributes',
                'additional_price',
                'image',
                'stock_count',
                'sold_count',
            ]);
        }

        return true;
    }

    private function deleteAllDetailsOfInventory($inventory_id)
    {
        return (bool) ProductInventoryDetails::where('inventory_id', $inventory_id)->delete();
    }
}
