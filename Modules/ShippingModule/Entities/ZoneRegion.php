<?php

namespace Modules\ShippingModule\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneRegion extends Model
{
    use HasFactory;
    protected $fillable = [
        'zone_id',
        'country',
        'state',
    ];
}
