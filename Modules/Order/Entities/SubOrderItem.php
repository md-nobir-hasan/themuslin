<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Campaign\Entities\CampaignProduct;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductInventoryDetail;

class SubOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "sub_order_id",
        "order_id",
        "product_id",
        "variant_id",
        "quantity",
        "price",
        "sale_price",
        "tax_amount",
        "tax_type"
    ];

    public $timestamps = false;

    public function campaignProduct(): HasOne
    {
        return $this->hasOne(CampaignProduct::class,"product_id","product_id");
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class,"id","product_id");
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class,"id","order_id");
    }

    public function variant(): HasOne
    {
        return $this->hasOne(ProductInventoryDetail::class,"id","variant_id");
    }

    public function productVariant(){
        return $this->belongsTo(ProductInventoryDetail::class,'variant_id');
    }

    protected static function newFactory()
    {
        return \Modules\Order\Database\factories\SubOrderItemFactory::new();
    }
}
