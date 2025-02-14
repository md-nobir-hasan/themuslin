<?php

namespace Modules\Attributes\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductInventoryDetail;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ["name","size_code","slug"];

    public $timestamps = false;

    public function product(){
        return $this->belongsToMany(Product::class, ProductInventoryDetail::class, "size","product_id","id","id");
    }
}
