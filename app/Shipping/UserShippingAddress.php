<?php

namespace App\Shipping;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Shipping\UserShippingAddress
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|UserShippingAddress newModelQuery()
 * @method static Builder|UserShippingAddress newQuery()
 * @method static Builder|UserShippingAddress query()
 * @method static Builder|UserShippingAddress whereAddress($value)
 * @method static Builder|UserShippingAddress whereCreatedAt($value)
 * @method static Builder|UserShippingAddress whereId($value)
 * @method static Builder|UserShippingAddress whereName($value)
 * @method static Builder|UserShippingAddress whereUpdatedAt($value)
 * @method static Builder|UserShippingAddress whereUserId($value)
 * @mixin Eloquent
 */
class UserShippingAddress extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address'
    ];

}
