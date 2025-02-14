<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Product\Entities\Product;
use function view;

class DeletedProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:deleted-product-list|deleted-product-restore|deleted-product-delete', ['only', ['index']]);
        $this->middleware('permission:deleted-product-restore', ['only', ['restore']]);
        $this->middleware('permission:deleted-product-delete', ['only', ['destroy', 'bulk_action']]);
    }

    public function index()
    {
        $all_deleted_products = Product::onlyTrashed()->with('category')->get();
        return view('product::backend..deleted.all', compact('all_deleted_products'));
    }

    public function restore($item)
    {
        $product = Product::withTrashed()->where('id', $item)->first();
        if ($product) {
            return $product->restore();
        }
        return false;
    }

    public function destroy($item)
    {
        $product = Product::withTrashed()->where('id', $item)->first();
        if ($product) {
            return $product->forceDelete();
        }
        return false;
    }

    public function bulk_action(Request $request)
    {
        $all_products = Product::onlyTrashed()
            ->whereIn('id', $request->ids)
            ->forceDelete();
        return 'ok';
    }
}
