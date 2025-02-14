<?php

namespace Modules\Order\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use LaravelIdea\Helper\Modules\Order\Entities\_IH_Order_QB;
use Modules\Order\Entities\Order;

trait OrderDetailsTrait
{
    private function orderDetailsMethod($orderId): Model|Builder|_IH_Order_QB|Order|null
    {
        // fetch order where requested id is exist
        // get order with suborders , paymentMeta, address and orderTrack
        return Order::where("id", $orderId)->with(["SubOrders" => function ($subOrderQuery){
            $subOrderQuery->with([
                "order",
                "vendor" => function ($vendorQuery){
                    $vendorQuery->withCount(["order as total_order" => function ($order){
                        $order->orderByDesc("orders.id");
                    },"order as complete_order" => function ($order){
                        $order->where("orders.order_status", "complete");
                    },"order as pending_order" => function ($order){
                        $order->where("orders.order_status", "pending");
                    },"product"])->withSum("subOrder as total_earning","sub_orders.total_amount");
                },
                "vendor.logo",
                "order.paymentMeta",
                "order.address",
                "order.address.country",
                "order.address.state",
                "order.address.cityInfo",
                "order.orderTrack",
                "orderItem",
                "productVariant.productColor",
                "productVariant.productSize",
                "productVariant",
                "product"
            ])->withCount("orderItem");
        },"paymentMeta", "address",
        "address.country",
        "address.state",
        "address.cityInfo", "orderTrack"])
        ->withCount("orderItems")->first();
    }
}