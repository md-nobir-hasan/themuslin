<?php

namespace Modules\Order\Traits;

use Illuminate\Support\Str;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderAddress;
use Modules\Order\Entities\OrderPaymentMeta;
use Modules\Order\Entities\OrderTrack;
use Modules\Order\Entities\SubOrder;
use Modules\Order\Entities\SubOrderItem;

trait StoreOrderTrait
{
    /**
     * @param $request
     * @return mixed
     */
    private static function storeOrder($request, $type=null): mixed
    {
        $self = (new self);
        $invoiceNumber = Order::select('id','invoice_number')->orderBy("id","desc")->first()?->invoice_number;

        $userId = null;
        if($type != 'pos'){
            if(auth('sanctum')->check()){
                $userId = auth('sanctum')->user()->id;
            }elseif (auth()->check()){
                $userId = auth()->user()->id;
            }
        }else{
            $userId = $request["selected_customer"] ?? null;
        }

        return Order::create([
            "coupon" => $request["coupon"] ?? "",
            "coupon_amount" => $self->couponAmount,
            "payment_track" => Str::random(10) . Str::random(10),
            "payment_gateway" => $request['payment_gateway'],
            "transaction_id" => Str::random(10) . Str::random(10),
            "order_status" => $request['payment_gateway'] == 'Wallet' ? 'complete' : 'pending' ,
            "payment_status" => $request['payment_gateway'] == 'Wallet' ? 'complete' : 'pending' ,
            "invoice_number" => $invoiceNumber ? $invoiceNumber + 1 : 111111,
            "user_id" => $userId,
            "type" => $type,
            "note" => $request["note"] ?? null,
            "selected_customer" => $request["selected_customer"] ?? null
        ]);
    }

    private static function storeOrderShippingAddress($data, $order_id){
        return OrderAddress::create(["order_id" => $order_id] + $data);
    }

    private static function storePaymentMeta($order_id, $sub_total, $coupon_amount, $shipping_cost,$tax_amount,$total_amount){
        return OrderPaymentMeta::create([
            "order_id" => $order_id,
            "sub_total" => $sub_total,
            "coupon_amount" => $coupon_amount,
            "shipping_cost" => $shipping_cost,
            "tax_amount" => $tax_amount,
            "total_amount" => $total_amount
        ]);
    }

    /**
     * @param $order_id
     * @param $name
     * @param $updated_by
     * @param $table
     * @return mixed
     */
    public static function storeOrderTrack($order_id, $name, $updated_by, $table): mixed
    {
        return OrderTrack::create([
            "order_id" => $order_id,
            "name" => $name, // This name is for orderTrack name as like order sent , order confirm
            "updated_by" => $updated_by,
            "table" => $table
        ]);
    }

    /**
     * @param $order_id
     * @param $vendor_id
     * @param $total_amount
     * @param $shipping_cost
     * @param $tax_amount
     * @param $order_address_id
     * @return mixed
     */
    private static function storeSubOrder($order_id, $vendor_id, $total_amount, $shipping_cost, $tax_amount, $type, $order_address_id): mixed
    {
        return SubOrder::create(array(
            "order_id" => $order_id,
            "vendor_id" => $vendor_id,
            "total_amount" => $total_amount,
            "shipping_cost" => $shipping_cost,
            "tax_amount" => $tax_amount,
            "tax_type" => $type,
            "order_address_id" => $order_address_id,
            "order_number" => rand(000000000000,999999999999),
            "payment_status" => 'pending',
            "order_status" => 'pending',
        ));
    }

    /**
     * @param $data
     * @return mixed
     */
    private static function storeSubOrderItem($data): mixed
    {
        return SubOrderItem::insert($data);
    }
}