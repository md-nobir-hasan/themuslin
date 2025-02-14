<?php

namespace Modules\TaxModule\Entities;

use Modules\CountryManage\Entities\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CountryTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'tax_percentage'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    protected static function newFactory()
    {
        return \Modules\TaxModule\Database\factories\CountryTaxFactory::new();
    }
}
