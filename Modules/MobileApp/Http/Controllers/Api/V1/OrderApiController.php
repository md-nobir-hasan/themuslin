<?php

namespace Modules\MobileApp\Http\Controllers\Api\V1;

use App\AdminShopManage;
use Illuminate\Routing\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Modules\Order\Services\CheckoutCouponService;
use Modules\Product\Entities\Product;
use Modules\ShippingModule\Entities\AdminShippingMethod;
use Modules\ShippingModule\Http\ShippingZoneServices;
use Modules\TaxModule\Entities\TaxClassOption;
use Modules\Vendor\Entities\Vendor;

class OrderApiController extends Controller
{
    public function shippingMethods(Request $request){
        // prepare data for send response
        $data = ShippingZoneServices::getMethods($request->id, $request->type);
        // after getting all data from shippingZoneServices send response

        return response()->json(['success' => true] + ((array) $data));
    }

    public function checkoutContents(Request $request){
        // first i have to get vendor information's hare i will accept vendor's id from request
        $vendor_ids = $request->vendor_ids;
        $default_vendor = $request->default_vendor;

        $shippingTaxAmount = TaxClassOption::where("class_id", get_static_option("shipping_tax_class"))->sum("rate");
        $vendors = Vendor::select("id","owner_name","business_name","status_id")
            ->whereIn("id", $vendor_ids)
            ->with(['shippingMethod' => function ($item){
                $item->where("status_id", 1);
            },'shippingMethod.zone'])->get()->transform(function ($item) use ($shippingTaxAmount){
                $status = $item->status->name;
                unset($item->status);
                unset($item->status_id);
                $item->status = $status;

                $item->shippingMethod->transform(function ($query) use ($shippingTaxAmount){
                    $query->cost = calculatePrice($query->cost, $shippingTaxAmount ?? 0, "shipping");

                    return $query;
                });

                return $item;
            });

        $adminShippingMethod = AdminShippingMethod::with("zone")->get()->transform(function ($item) use ($shippingTaxAmount) {
            $item->cost = calculatePrice($item->cost, $shippingTaxAmount ?? 0, "shipping");

            return $item;
        });

        $adminShopManage = AdminShopManage::select("id","store_name")->first();

        return ["vendors" => $vendors,"default_vendor" => ["shipping_methods" => $adminShippingMethod, "adminShop" => $adminShopManage]];
    }

    public function applyCoupon(Request $request){
        $productIds = $request->product_ids;
        $products = Product::with("category", "subCategory", "childCategory")->whereIn('id', $productIds)->get();
        $subtotal = $request->subTotal;

        $coupon_amount_total = CheckoutCouponService::calculateCoupon($request, $subtotal, $products, 'DISCOUNT');

        if ($coupon_amount_total == 0) {
            return response()->json(['type' => 'error', 'msg' => 'Please enter a valid coupon code']);
        }

        if ($coupon_amount_total) {
            return response()->json([
                'type' => 'success',
                'coupon_amount' => toFixed($coupon_amount_total),
                'msg' => __('Coupon applied successfully')
            ]);
        }

        return response()->json(['type' => 'danger', 'coupon_amount' => 0]);
    }
}