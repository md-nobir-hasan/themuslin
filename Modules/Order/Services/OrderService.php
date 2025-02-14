<?php

namespace Modules\Order\Services;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\Order\Abstract\OrderAbstract;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\SubOrder;
use Modules\Order\Traits\OrderTrait;

class OrderService extends OrderAbstract
{
    private string $totalAmount;
    private string $couponAmount;
    private string $subTotal;
    private string $taxAmount;
    private string $shippingCost;
    private static $self;
    private static $vendor_ids;

    use OrderTrait;

    public function __construct()
    {
        $this->couponAmount = 0;
    }

    public static function createOrder($data){
        // CREATE AN INSTANCE OF THIS CLASS FOR ACCESSING THIS CLASS NON-STATIC  METHOD AND PROPERTIES
        
        // STORE ORDER DATA INTO ORDER TABLE AND ASSIGN IN ORDER VARIABLE FOR USING NEXT TIME
        // run a foreach for creating every vendor order or admin order
        foreach(self::groupByCartData() as $key => $value) :
            if(empty($key)){
                return $value;
            }else{
                return $value;
            }
        endforeach;
    }
    private static function groupByCartData(): Collection
    {
        return Cart::instance("default")->content()->groupBy("options.vendor_id");
    }

    public static function subOrderDetails($id, $type="admin"): Model|Factory|View|Builder|Application|null
    {
        $subOrders = SubOrder::with([
            "order",
            "vendor" => function ($vendorQuery){
                $vendorQuery->withCount(["order as total_order" => function ($order){
                        $order->orderByDesc("orders.id");
                    },"order as complete_order" => function ($order){
                        $order->where("orders.order_status", "complete");
                    },"order as pending_order" => function ($order){
                        $order->where("orders.order_status", "pending");
                    },"product"])
                    ->withSum("subOrder as total_earning", "sub_orders.total_amount");
            },
            "vendor.logo",
            "order.paymentMeta",
            "order.address",
            "order.address.country",
            "order.address.state",
            "order",
            "orderItem",
            "productVariant.productColor",
            "productVariant.productSize",
            "productVariant",
            "product",
            "orderTrack" => function ($query){
                return $query->orderByDesc('id')->limit(1);
            }
        ])->withCount("orderItem")
        ->where("id", $id)->first();

        if($type == "vendor"){
            return view("order::vendor.details", compact("subOrders"));
        }elseif ($type == "vendor-api"){
            return $subOrders;
        }

        return view("order::admin.order-details", compact("subOrders"));
    }
}