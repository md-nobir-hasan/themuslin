<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryDetailsAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'inventory_details_id',
        'attribute_name',
        'attribute_value',
    ];

    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\InventoryDetailsAttributeFactory::new();
    }
}
