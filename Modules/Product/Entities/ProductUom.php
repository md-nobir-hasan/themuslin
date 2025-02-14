<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Attributes\Entities\Unit;

class ProductUom extends Model
{
    protected $fillable = ["product_id","unit_id","quantity"];

    protected $table = 'product_uom';

    public $timestamps = false;

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, "unit_id", "id");
    }
}
