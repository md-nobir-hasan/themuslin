<?php

namespace Modules\Coupon\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCoupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'code',
        'discount',
        'discount_type',
        'discount_on',
        'discount_on_details',
        'expire_date',
        'status'
    ];
}
