<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductChildCategory extends Model
{
    use HasFactory;

    protected $fillable = ["child_category_id","product_id"];
    
    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductChildCategoryFactory::new();
    }
}
