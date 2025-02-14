<?php

namespace App\Shipping;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\CountryManage\Entities\City;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;

/**
 * App\Shipping\ShippingAddress
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $user_id
 * @property int|null $country_id
 * @property int|null $state_id
 * @property string|null $city
 * @property string|null $zip_code
 * @property string|null $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Country|null $country
 * @property-read State|null $state
 * @method static Builder|ShippingAddress newModelQuery()
 * @method static Builder|ShippingAddress newQuery()
 * @method static Builder|ShippingAddress query()
 * @method static Builder|ShippingAddress whereAddress($value)
 * @method static Builder|ShippingAddress whereCity($value)
 * @method static Builder|ShippingAddress whereCountryId($value)
 * @method static Builder|ShippingAddress whereCreatedAt($value)
 * @method static Builder|ShippingAddress whereEmail($value)
 * @method static Builder|ShippingAddress whereId($value)
 * @method static Builder|ShippingAddress whereName($value)
 * @method static Builder|ShippingAddress wherePhone($value)
 * @method static Builder|ShippingAddress whereStateId($value)
 * @method static Builder|ShippingAddress whereUpdatedAt($value)
 * @method static Builder|ShippingAddress whereUserId($value)
 * @method static Builder|ShippingAddress whereZipCode($value)
 * @mixin Eloquent
 */
class ShippingAddress extends Model
{
    protected $fillable = [
        'name',
        'email',
        'shipping_address_name',
        'phone',
        'user_id',
        'country_id',
        'state_id',
        'city',
        'zip_code',
        'address',
        'default_shipping_status'
    ];

    public function get_states(): HasMany
    {
        return $this->hasMany(State::class, "country_id", "country_id");
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function cities(): BelongsTo
    {
        return $this->belongsTo(City::class, "city", "id");
    }
}
