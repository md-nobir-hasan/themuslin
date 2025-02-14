<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderPaymentMeta extends Model
{
    use HasFactory;

    protected $fillable = [
        "order_id",
        "sub_total",
        "coupon_amount",
        "shipping_cost",
        "tax_amount",
        "total_amount",
        "payment_attachments",
        "gateway_return",
        "gateway_trx_id"
    ];

    public $timestamps = false;

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
