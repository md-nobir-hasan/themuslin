<?php

namespace Modules\Vendor\Entities;

use App\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use Modules\Vendor\Database\factories\VendorAddressFactory;

class VendorAddress extends Model
{
    use HasFactory;

    protected $fillable = ["vendor_id","country_id","state_id","city_id","zip_code","address"];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    protected static function newFactory()
    {
        return VendorAddressFactory::new();
    }
}
