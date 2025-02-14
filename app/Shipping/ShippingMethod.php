<?php

namespace App\Shipping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Shipping\ShippingMethod
 *
 * @property int $id
 * @property string $name
 * @property int|null $zone_id
 * @property int $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Shipping\ShippingMethodOption|null $availableOptions
 * @property-read \App\Shipping\ShippingMethodOption|null $options
 * @property-read \App\Shipping\Zone|null $zone
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingMethod whereZoneId($value)
 * @mixin \Eloquent
 */
class ShippingMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'zone_id',
        'is_default',
    ];

    protected $with = ['options'];

    public function options()
    {
        return $this->hasOne(ShippingMethodOption::class, 'shipping_method_id', 'id');
    }

    public function availableOptions()
    {
        return $this->hasOne(ShippingMethodOption::class, 'shipping_method_id', 'id')->where('status', 1);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
