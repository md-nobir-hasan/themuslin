<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class OrderTrack extends Model
{

    use HasEagerLimit;

    protected $fillable = [
        "order_id",
        "name",
        "updated_by",
        "table",
        "created_at"
    ];

    protected $casts = [
        "created_at" => "datetime"
    ];

    public $timestamps = false;
}
