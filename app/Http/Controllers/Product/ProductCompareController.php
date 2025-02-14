<?php

namespace Modules\CountryManage\Http\Controllers\Product;

use App\Helpers\CompareHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductCompareController extends Controller
{
    public function addToCompare(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        return CompareHelper::add($request->product_id);
    }

    public function removeFromCompare(Request $request)
    {
        $request->validate(['id' => 'required|exists:products']);

        return CompareHelper::remove($request->id);
    }

    public function clearCompare()
    {
        return CompareHelper::clear();
    }
}
