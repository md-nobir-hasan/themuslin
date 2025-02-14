<?php

namespace Modules\Vendor\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Vendor\Database\factories\VendorBankInfoFactory;

class VendorBankInfo extends Model
{
    use HasFactory;

    protected $fillable = ["vendor_id","bank_name","bank_email","bank_code","account_number"];
    
    protected static function newFactory()
    {
        return VendorBankInfoFactory::new();
    }
}
