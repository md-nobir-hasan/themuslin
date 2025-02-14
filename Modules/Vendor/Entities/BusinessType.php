<?php

namespace Modules\Vendor\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','description'];
    
    protected static function newFactory()
    {
        return \Modules\Vendor\Database\factories\BusinessTypeFactory::new();
    }
}

