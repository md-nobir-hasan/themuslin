<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;

class AdminShopManage extends Model
{
    use HasFactory;

    protected $fillable = [
        "store_name",
        "logo_id",
        "cover_photo_id",
        "country_id",
        "state_id",
        "city",
        "zipcode",
        "address",
        "location",
        "number",
        "email",
        "facebook_url"
    ];

    public function logo(): HasOne
    {
        return $this->hasOne(MediaUpload::class,"id","logo_id");
    }

    public function cover_photo(): HasOne
    {
        return $this->hasOne(MediaUpload::class,"id","cover_photo_id");
    }

    public function country(): HasOne
    {
        return $this->hasOne(Country::class,"id", "country_id");
    }

    public function state(): HasOne
    {
        return $this->hasOne(State::class,"id", "state_id");
    }
}
