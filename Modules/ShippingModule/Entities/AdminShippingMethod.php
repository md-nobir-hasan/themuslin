<?php

namespace Modules\ShippingModule\Entities;

use App\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdminShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "cost",
        "status_id",
        "zone_id",
        "vendor_id",
        "is_default"
    ];

    public function zone(): HasOne
    {
        return $this->hasOne(Zone::class,"id","zone_id");
    }

    public function status(): HasOne
    {
        return $this->hasOne(Status::class,"id","status_id");
    }
}
