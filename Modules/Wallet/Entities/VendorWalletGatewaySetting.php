<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Vendor\Entities\Vendor;

class VendorWalletGatewaySetting extends Model
{
    protected $fillable = [
        "vendor_id",
        "vendor_wallet_gateway_id",
        "fileds"
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function vendorWalletGateway(): BelongsTo
    {
        return $this->belongsTo(VendorWalletGateway::class);
    }

    public $timestamps = false;
}