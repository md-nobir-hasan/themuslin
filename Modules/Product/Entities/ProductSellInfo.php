<?php

namespace Modules\Product\Entities;

use App\Shipping\UserShippingAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSellInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'user_id', // buyer - nullable
        'country',
        'address',
        'city',
        'state',
        'zipcode',
        'phone',

        'product_id',
        'coupon',
        'coupon_discounted',
        'total_amount',
        'status',

        'payment_status',
        'payment_gateway',
        'payment_track',
        'transaction_id',
        'checkout_image_path',

        'order_details',
        'payment_meta',
        'shipping_address_id', // UserShippingAddress->id

        'selected_shipping_option'
    ];

    public function shipping()
    {
        return $this->hasOne(UserShippingAddress::class, 'id', 'shipping_address_id');
    }

    public function sale_details()
    {
        return $this->hasMany(SaleDetails::class, 'order_id', 'id');
    }
}
