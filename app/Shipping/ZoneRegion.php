<?php

namespace App\Shipping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Shipping\ZoneRegion
 *
 * @property int $id
 * @property int $zone_id
 * @property string|null $country
 * @property string|null $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneRegion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneRegion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneRegion query()
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneRegion whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneRegion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneRegion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneRegion whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneRegion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneRegion whereZoneId($value)
 * @mixin \Eloquent
 */
class ZoneRegion extends Model
{
    use HasFactory;
    protected $fillable = [
        'zone_id',
        'country',
        'state',
    ];
}
