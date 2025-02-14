<?php

namespace App\Shipping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Shipping\ShippingMethodOption
 *
 * @property int $id
 * @property string $title
 * @property int $shipping_method_id
 * @property int $status
 * @property int $tax_status
 * @property string|null $setting_preset
 * @property float $cost
 * @property float|null $minimum_order_amount
 * @property string|null $coupon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption whereCoupon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption whereMinimumOrderAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption whereSettingPreset($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption whereShippingMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption whereTaxStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethodOption whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShippingMethodOption extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'shipping_method_id',
        'status',
        'tax_status',
        'cost',
        'coupon',
        'setting_preset',
        'minimum_order_amount',
    ];

    protected $casts = [
        'cost' => 'float',
        'minimum_order_amount' => 'float',
    ];
}
