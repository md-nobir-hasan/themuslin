<?php

namespace Modules\Product\Entities;

use App\Http\Traits\NotificationRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductInventory extends Model
{
    use NotificationRelation;

    protected $fillable = ["product_id","sku","stock_count","sold_count"];

    public $timestamps = false;

    public function inventoryDetails(): HasMany
    {
        return $this->hasMany(ProductInventoryDetail::class,"product_inventory_id","id");
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class,"id","product_id");
    }
}
