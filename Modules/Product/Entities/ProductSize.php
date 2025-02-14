<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Extra Large
        'size_code', // XL
        'slug', // extra-large
    ];
    
    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductSizeFactory::new();
    }
}
