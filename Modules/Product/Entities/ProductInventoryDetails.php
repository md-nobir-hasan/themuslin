<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductInventoryDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'product_id',
        'hash', // used to find product based on selected attributes
        'color',
        'size',
        'additional_price',
        'image',
        'stock_count',
        'sold_count',
    ];

    public function productColor(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class, 'color', 'id');
    }

    public function productSize(): BelongsTo
    {
        return $this->belongsTo(ProductSize::class, 'size', 'id');
    }

    public function includedAttributes(): HasMany
    {
        return $this->hasMany(InventoryDetailsAttribute::class, 'inventory_details_id', 'id');
    }
}
