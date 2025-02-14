<?php

namespace Modules\Wallet\Entities;

use App\Observers\WalletBalanceObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\DeliveryMan\Entities\DeliveryMan;
use Modules\User\Entities\User;
use Modules\Vendor\Entities\Vendor;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','vendor_id','delivery_man_id','balance','pending_balance','status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class,'vendor_id','id');
    }

    public function deliveryMan(): BelongsTo
    {
        return $this->belongsTo(DeliveryMan::class, "delivery_man_id","id");
    }

    protected static function newFactory()
    {
        return \Modules\Wallet\Database\factories\WalletFactory::new();
    }
}
