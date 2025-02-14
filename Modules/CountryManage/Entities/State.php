<?php

namespace Modules\CountryManage\Entities;

use Modules\CountryManage\Entities\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\ShippingModule\Entities\ShippingMethod;
use Modules\ShippingModule\Entities\ShippingMethodOption;
use Modules\ShippingModule\Entities\Zone;
use Modules\ShippingModule\Entities\ZoneCountry;
use Modules\ShippingModule\Entities\ZoneState;
use Modules\TaxModule\Entities\StateTax;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class State extends Model
{
    use HasRelationships;

    protected $fillable = [
        'name',
        'country_id',
        'status',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function stateTax(): HasOne
    {
        return $this->hasOne(StateTax::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class,"state_id","id");
    }

    public function stateShippingMethods(): HasManyDeep
    {
        return $this->hasManyDeep(ShippingMethodOption::class, [ZoneState::class,ZoneCountry::class, Zone::class, ShippingMethod::class], [
            "state_id",
            "id",
            "id",
            "zone_id",
            "shipping_method_id",
        ],[
            "id",
            "zone_country_id",
            "zone_id",
            "id",
            "id",
        ]);
    }
}
