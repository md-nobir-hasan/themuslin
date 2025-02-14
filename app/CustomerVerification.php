<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVerification extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'entity_type',
        'entity_value',
        'verification_code',
        'verified_status',
        'expired',
        'created_at',
        'updated_at'
    ];
}
