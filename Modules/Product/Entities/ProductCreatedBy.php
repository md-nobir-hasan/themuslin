<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCreatedBy extends Model
{
    use HasFactory;

    protected $fillable = [
        "product_id",
        "created_by_id",
        "guard_name",
        "updated_by",
        "updated_by_guard",
        "deleted_by",
        "deleted_by_guard"
    ];

    public $timestamps = false;

    protected $table = "product_created_by";
    
    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductCreatedByFactory::new();
    }
}
