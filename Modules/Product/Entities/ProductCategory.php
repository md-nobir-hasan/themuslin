<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = ["category_id","product_id"];

    public $timestamps = false;

    public function product(){
        return $this->belongsTo(Product::class, "product_id", "id");
    }
}
