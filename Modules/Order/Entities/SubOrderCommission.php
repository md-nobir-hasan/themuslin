<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Square\Models\Vendor;

class SubOrderCommission extends Model
{
    protected $fillable = [
        'vendor_id',
        'sub_order_id',
        'commission_type',
        'commission_amount',
        'is_individual_commission',
    ];

    protected $casts = [
        "commission_amount" => "double"
    ];

    public function sub_order(): BelongsTo
    {
        return $this->belongsTo(SubOrder::class,"sub_order_id","id");
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class,"vendor_id","id");
    }
}
