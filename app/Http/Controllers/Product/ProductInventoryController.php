<?php

namespace Modules\CountryManage\Http\Controllers\Product;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetails;

class ProductInventoryController extends Controller
{
    const BASE_URL = 'backend.products.inventory.';

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
     * @param  \Modules\Product\Entities\ProductInventory  $productInventory
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductInventory $item)
    {
        $inventory = $item->where('id', $item->id)->with('details')->first();
        $all_products = Product::all();
        $all_attributes = ProductAttribute::select('id', 'title', 'terms')->get();

        return view(self::BASE_URL.'edit')->with([
            'inventory' => $inventory,
            'all_products' => $all_products,
            'all_attributes' => $all_attributes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\Product\Entities\ProductInventory  $productInventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku' => 'required|string',
            'stock_count' => 'required|numeric',
            'inventory_details' => 'nullable|array',
        ]);

        try {
            Db::beginTransaction();

            $product_inventory = ProductInventory::find($request->id);

            $product_inventory_updated = $product_inventory->update([
                'product_id' => $request->sanitize_html('product_id'),
                'sku' => $request->sanitize_html('sku'),
                'stock_count' => $request->sanitize_html('stock_count'),
            ]);

            $this->deleteAllDetailsOfInventory($product_inventory->id);

            if ($request->inventory_details && count($request->inventory_details)) {
                $this->insertInventoryDetails($product_inventory->id, $request->inventory_details);
            }

            DB::commit();

            return response()->json(FlashMsg::update_succeed(__('Product Inventory')));
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(FlashMsg::update_failed(__('Product Inventory')), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modules\Product\Entities\ProductInventory  $productInventory
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

    /** =========================================================================
     *                          HELPER FUNCTIONS
      ========================================================================= */
    private function insertInventoryDetails($inventory_id, $inventory_details)
    {
        foreach ($inventory_details as $details) {
            $product_inventory_details = ProductInventoryDetails::create([
                'inventory_id' => $inventory_id,
                'attribute_id' => $details['attribute_id'],
                'attribute_value' => $details['attribute_value'],
                'stock_count' => $details['stock_count'],
            ]);
        }

        return true;
    }

    private function deleteAllDetailsOfInventory($inventory_id)
    {
        return (bool) ProductInventoryDetails::where('inventory_id', $inventory_id)->delete();
    }
}
