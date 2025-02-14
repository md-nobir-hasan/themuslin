<?php

namespace Modules\Wallet\Entities;

use App\Http\Traits\NotificationRelation;
use App\MediaUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Vendor\Entities\Vendor;

class VendorWithdrawRequest extends Model
{
    use NotificationRelation;

    protected $fillable = [
        "amount",
        "gateway_id",
        "vendor_id",
        "request_status",
        "gateway_fields",
        "note",
        "image",
    ];

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(VendorWalletGateway::class,"gateway_id","id");
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class,"vendor_id","vendor_id");
    }
}