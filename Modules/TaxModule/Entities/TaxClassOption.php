<?php

namespace Modules\TaxModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\CountryManage\Entities\City;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;

class TaxClassOption extends Model
{
    protected $fillable = [
        'class_id',
        'tax_name',
        'country_id',
        'state_id',
        'city_id',
        'postal_code',
        'priority',
        'is_compound',
        'is_shipping',
        'rate',
    ];

    public function taxClass(): BelongsTo
    {
        return $this->belongsTo(TaxClass::class,"class_id","id");
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class,"country_id","id");
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class,"state_id","id");
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class,"city_id","id");
    }

    public function states(): HasMany
    {
        return $this->hasMany(State::class,"country_id", "country_id");
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class,"state_id", "state_id");
    }
}
