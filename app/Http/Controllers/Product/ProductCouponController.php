<?php

namespace Modules\CountryManage\Http\Controllers\Product;

use App\Enums\CouponEnum;
use App\Helpers\FlashMsg;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductCoupon;
use Modules\Product\Entities\ProductSubCategory;

class ProductCouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-coupon-list|product-coupon-create|product-coupon-edit|product-coupon-delete', ['only', ['index']]);
        $this->middleware('permission:product-coupon-create', ['only', ['store']]);
        $this->middleware('permission:product-coupon-edit', ['only', ['update']]);
        $this->middleware('permission:product-coupon-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $all_product_coupon = ProductCoupon::all();
        $coupon_apply_options = CouponEnum::discountOptions();
        $all_categories = ProductCategory::where('status', 'publish')->get();
        $all_subcategories = ProductSubCategory::where('status', 'publish')->get();

        return view('backend.products.coupon.all-coupon')->with([
            'all_product_coupon' => $all_product_coupon,
            'coupon_apply_options' => $coupon_apply_options,
            'all_categories' => $all_categories,
            'all_subcategories' => $all_subcategories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'code' => 'required|string|max:191|unique:product_coupons',
            'discount_on' => 'required|string|max:191',
            'category' => 'nullable|numeric',
            'subcategory' => 'nullable|numeric',
            'products' => 'nullable|array',
            'discount' => 'required|string|max:191',
            'discount_type' => 'required|string|max:191',
            'expire_date' => 'required|string|max:191',
            'status' => 'required|string|max:191',
        ]);

        $discount_details = '';
        if ($request->discount_on == 'category') {
            $discount_details = json_encode($request->category);
        } elseif ($request->discount_on == 'subcategory') {
            $discount_details = json_encode($request->subcategory);
        } elseif ($request->discount_on == 'product') {
            $products = sanitizeArray($request->products);
            $discount_details = json_encode($products);
        }

        $product_coupon = ProductCoupon::create([
            'title' => $request->sanitize_html('title'),
            'code' => $request->sanitize_html('code'),
            'discount' => $request->sanitize_html('discount'),
            'discount_type' => $request->sanitize_html('discount_type'),
            'expire_date' => $request->sanitize_html('expire_date'),
            'status' => $request->sanitize_html('status'),
            'discount_on' => $request->sanitize_html('discount_on'),
            'discount_on_details' => $discount_details,
        ]);

        return $product_coupon->id
            ? back()->with(FlashMsg::create_succeed(' Coupon'))
            : back()->with(FlashMsg::create_failed(' Coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\Product\Entities\ProductCoupon  $productCoupon
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'code' => 'required|string|max:191',
            'discount_on' => 'required|string|max:191',
            'category' => 'nullable|numeric',
            'subcategory' => 'nullable|numeric',
            'products' => 'nullable|array',
            'discount' => 'required|string|max:191',
            'discount_type' => 'required|string|max:191',
            'expire_date' => 'required|string|max:191',
            'status' => 'required|string|max:191',
        ]);

        $discount_details = '';
        if ($request->discount_on == 'category') {
            $discount_details = json_encode($request->category);
        } elseif ($request->discount_on == 'subcategory') {
            $discount_details = json_encode($request->subcategory);
        } elseif ($request->discount_on == 'product') {
            $products = sanitizeArray($request->products);
            $discount_details = json_encode($products);
        }

        $updated = ProductCoupon::find($request->id)->update([
            'title' => $request->sanitize_html('title'),
            'code' => $request->code,
            'discount' => $request->discount,
            'discount_type' => $request->discount_type,
            'expire_date' => $request->expire_date,
            'status' => $request->status,
            'discount_on' => $request->sanitize_html('discount_on'),
            'discount_on_details' => $discount_details,
        ]);

        return $updated
            ? back()->with(FlashMsg::update_succeed(' Coupon'))
            : back()->with(FlashMsg::update_failed(' Coupon'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modules\Product\Entities\ProductCoupon  $productCoupon
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProductCoupon $item)
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed(' Coupon'))
            : back()->with(FlashMsg::delete_failed(' Coupon'));
    }

    public function check(Request $request)
    {
        return (bool) ProductCoupon::where('code', $request->code)->count();
    }

    public function bulk_action(Request $request)
    {
        ProductCoupon::whereIn('id', $request->ids)->delete();

        return response()->json(['status' => 'ok']);
    }

    public function allProductsAjax()
    {
        $all_products = Product::select('id', 'title')->where('status', 'publish')->get();

        return response()->json($all_products);
    }
}
