<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Order\Entities\SubOrder;
use Modules\User\Entities\User;
use Modules\Vendor\Entities\Vendor;

class WalletHistory extends Model
{
    protected $fillable = [
        "wallet_id",
        "user_id",
        "vendor_id",
        "sub_order_id",
        "amount",
        "transaction_id",
        "type",
        "payment_gateway",
        "payment_status"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, "wallet_id", "id");
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function sub_order(): BelongsTo
    {
        return $this->belongsTo(SubOrder::class, "sub_order_id", "id");
    }
}
