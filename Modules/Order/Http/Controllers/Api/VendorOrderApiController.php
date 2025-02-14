<?php

namespace Modules\Order\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\SubOrder;
use Modules\Order\Http\Resources\VendorOrderDetailsResource;
use Modules\Order\Http\Resources\VendorOrderListResource;
use Modules\Order\Services\OrderService;

class VendorOrderApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        // first of all we need to get all sub order for login user
        $all_orders = SubOrder::with([
                "order:id,payment_status,order_status,created_at",
                "orderTrack" => function ($query){
                    $query->latest('id')->limit(1);
                }
            ])->where("vendor_id", auth("sanctum")->id())
            ->withCount("orderItem")
            ->orderByDesc("id")->paginate(20);

        return VendorOrderListResource::collection($all_orders);
    }

    public function details($id){
        $order = OrderService::subOrderDetails($id, "vendor-api");

        if(empty($order)){
            return response()->json([
                "msg" => __("Order not found"),
                "status" => false
            ], 404);
        }

        return new VendorOrderDetailsResource($order);
    }
}
