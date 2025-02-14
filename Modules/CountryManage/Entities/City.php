<?php

namespace Modules\CountryManage\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LaravelIdea\Helper\Modules\CountryManage\Entities\_IH_City_C;

class City extends Model
{
    protected $fillable = ['name','country_id','state_id','status'];

    protected $casts = [
        "country_id" => "integer",
        "state_id" => "integer"
    ];

    public static function all_cities(): array|_IH_City_C
    {
        return self::select(['id','city','country_id','state_id','status'])
            ->where('status',1)->get();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
