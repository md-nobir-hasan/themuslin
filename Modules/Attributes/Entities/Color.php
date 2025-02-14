<?php

namespace Modules\Attributes\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductInventoryDetail;

class Color extends Model
{
    use HasFactory;

    protected $fillable = ["name","color_code","slug"];

    public $timestamps = false;

    public function product(){
        return $this->belongsToMany(Product::class, ProductInventoryDetail::class, "color","product_id","id","id");
    }
}
