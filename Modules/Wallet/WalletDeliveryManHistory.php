<?php

namespace Modules\Wallet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Order\Entities\Order;
use Modules\Wallet\Entities\Wallet;

class WalletDeliveryManHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'wallet_id',
        'amount',
        'transaction_id',
        'type',
        'order_id'
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class,"wallet_id","id");
    }
}
