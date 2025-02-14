<?php

namespace Modules\TaxModule\Entities;

use Modules\CountryManage\Entities\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StateTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'tax_percentage',
        'country_id'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    protected static function newFactory()
    {
        return \Modules\TaxModule\Database\factories\StateTaxFactory::new();
    }
}
