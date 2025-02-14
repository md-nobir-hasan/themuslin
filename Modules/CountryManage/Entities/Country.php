<?php

namespace Modules\CountryManage\Entities;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\CountryManage\Entities\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ShippingModule\Entities\ShippingMethod;
use Modules\ShippingModule\Entities\ShippingMethodOption;
use Modules\ShippingModule\Entities\Zone;
use Modules\ShippingModule\Entities\ZoneCountry;
use Modules\ShippingModule\Entities\ZoneState;
use Modules\TaxModule\Entities\CountryTax;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Country extends Model
{
    use HasFactory;
    use HasRelationships;

    protected $fillable = [
        'name',
        'status',
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function zoneStates(): HasManyDeep
    {
        return $this->hasManyDeep(State::class, [ZoneCountry::class,ZoneState::class],[
            "country_id",
            "zone_country_id",
            "id",
        ],[
            "id",
            "id",
            "state_id"
        ]);
    }

    public function shippingMethods(): HasManyDeep
    {
        return $this->hasManyDeep(ShippingMethodOption::class, [ZoneCountry::class, Zone::class, ShippingMethod::class], [
            "country_id",
            "id",
            "zone_id",
            "shipping_method_id",
        ],[
            "id",
            "zone_id",
            "id",
            "id",
        ]);
    }

    public function countryTax(): HasOne
    {
        return $this->hasOne(CountryTax::class);
    }
}
