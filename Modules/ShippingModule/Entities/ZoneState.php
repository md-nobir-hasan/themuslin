<?php

namespace Modules\ShippingModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\CountryManage\Entities\State;

class ZoneState extends Model
{
    use HasFactory;

    protected $fillable = ["zone_country_id","state_id"];

    public $timestamps = false;

    public function zoneCountry(): HasOne
    {
        return $this->hasOne(ZoneCountry::class, "id","zone_country_id");
    }

    public function country(){

    }

    public function state(): HasOne
    {
        return $this->hasOne(State::class, "id", "state_id");
    }
}
