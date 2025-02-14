<?php

namespace Modules\ShippingModule\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Modules\CountryManage\Entities\Country;

class Zone extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function zoneCountry(): HasMany
    {
        return $this->hasMany(ZoneCountry::class, "zone_id", "id");
    }

    public function country(): HasManyThrough
    {
        return $this->hasManyThrough(Country::class, ZoneCountry::class,"zone_id","id","id", "country_id");
    }
}
