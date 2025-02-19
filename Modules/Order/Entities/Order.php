<?php

namespace Modules\Order\Entities;

use App\Http\Traits\NotificationRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\DeliveryMan\Entities\DeliveryManOrder;
use Modules\Refund\Entities\RefundRequest;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Order extends Model
{
    use HasEagerLimit,NotificationRelation;

    protected $fillable = [
        "coupon",
        "coupon_amount",
        "payment_track",
        "payment_gateway",
        "transaction_id",
        "order_status",
        "payment_status",
        "invoice_number",
        "user_id",
        "type",
        "note",
        "selected_customer",
        "shipmenttrackingnumber",
        "shipping_id"
    ];

    public function deliveryMan(): HasOne
    {
        return $this->hasOne(DeliveryManOrder::class, "order_id", "id");
    }

    public function paymentMeta(): HasOne
    {
        return $this->hasOne(OrderPaymentMeta::class,"order_id","id");
    }

    public function address(): HasOne
    {
        return $this->hasOne(OrderAddress::class, "order_id", "id");
    }

    public function orderTrack(): HasMany
    {
        return $this->hasMany(OrderTrack::class,"order_id","id");
    }

    public function SubOrders(): HasMany
    {
        return $this->hasMany(SubOrder::class,"order_id","id");
    }

    public function subOrder(): HasOne
    {
        return $this->hasOne(SubOrder::class,"order_id","id");
    }


    public function orderItems(): HasManyThrough
    {
        return $this->hasManyThrough(SubOrderItem::class,SubOrder::class,"order_id","sub_order_id","id","id");
    }

    public function isDelivered(): HasOne
    {
        return $this->hasOne(OrderTrack::class,"order_id","id")->where("name","delivered");
    }

    public function refundRequest(): HasOne
    {
        return $this->hasOne(RefundRequest::class,"order_id","id");
    }
}
