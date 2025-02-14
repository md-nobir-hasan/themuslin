<?php

namespace Modules\Attributes\Entities;

use App\MediaUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\Product;

class Brand extends Model
{
    use HasFactory,SoftDeletes;

    protected $with = ['logo'];

    protected $fillable = ["name","slug","description","title","image_id","banner_id"];

    public function logo(): HasOne
    {
        return $this->hasOne(MediaUpload::class,"id","image_id");
    }

    public function banner(): HasOne
    {
        return $this->hasOne(MediaUpload::class,"id","banner_id");
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'brand_id','id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id','id');
    }
}
