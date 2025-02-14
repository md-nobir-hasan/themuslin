<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'order_id',
        'attributes',
        'quantity',
        'price',
    ];

    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\SaleDetailsFactory::new();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id', 'id');
    }

    public function product_sell_info(){
        return $this->belongsTo(ProductSellInfo::class,"order_id","id");
    }
}
