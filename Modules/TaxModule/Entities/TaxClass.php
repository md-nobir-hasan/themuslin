<?php

namespace Modules\TaxModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CountryManage\Entities\State;

class TaxClass extends Model
{
    protected $fillable = [
        'name',
    ];

    public function classOption(): HasMany
    {
        return $this->hasMany(TaxClassOption::class,"class_id","id");
    }
}
