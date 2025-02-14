<?php

namespace Modules\MobileApp\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileFeaturedProduct extends Model
{
    use HasFactory;

    protected $fillable = ["type","ids"];

    public $timestamps = false;
}
