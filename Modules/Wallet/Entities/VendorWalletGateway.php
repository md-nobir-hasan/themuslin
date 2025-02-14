<?php

namespace Modules\Wallet\Entities;

use App\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorWalletGateway extends Model
{
    protected $fillable = [
        "name",
        "filed",
        "status_id",
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}