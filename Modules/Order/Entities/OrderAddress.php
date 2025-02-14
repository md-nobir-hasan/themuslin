<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\CountryManage\Entities\City;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;

class OrderAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        "order_id",
        "name",
        "email",
        "phone",
        "country_id",
        "state_id",
        "city",
        "address",
        "user_id",
        "zipcode"
    ];

    public $timestamps = false;

    public function country(): HasOne
    {
        return $this->hasOne(Country::class, "id", "country_id");
    }

    public function state(): HasOne
    {
        return $this->hasOne(State::class, "id", "state_id");
    }

    public function cityInfo(): BelongsTo
    {
        return $this->belongsTo(City::class,"city","id");
    }
}
