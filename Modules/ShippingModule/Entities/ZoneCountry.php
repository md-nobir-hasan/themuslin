<?php

namespace Modules\ShippingModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;

class ZoneCountry extends Model
{
    use HasFactory;

    protected $fillable = ["country_id","zone_id"];

    public $timestamps = false;

    public function zone(): HasOne
    {
        return $this->hasOne(Zone::class,"id", "zone_id");
    }

    public function country(): HasOne
    {
        return $this->hasOne(Country::class, "id", "country_id");
    }

    public function zoneStates(): HasMany
    {
        return $this->hasMany(ZoneState::class, "zone_country_id","id");
    }

    public function states(): HasManyThrough
    {
        return $this->hasManyThrough(State::class, ZoneState::class, "zone_country_id","id","id","state_id");
    }
}
