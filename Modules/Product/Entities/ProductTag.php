<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductTag extends Model
{
    use HasFactory;

    protected $fillable = ["product_id", "tag_name"];
    
    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductTagFactory::new();
    }
}
