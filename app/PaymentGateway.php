<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaymentGateway extends Model
{
    use HasFactory;
    protected $table = 'payment_gateways';

    protected $with = ['oldImage'];

    protected $fillable = ['name','image','description','status','test_mode','credentials'];

    protected $casts = [
        'test_mode' => 'integer',
        'status' => 'integer'
    ];

    public function oldImage(): HasOne
    {
        return $this->hasOne(MediaUpload::class,"id","image");
    }
}
