<?php

namespace Modules\Attributes\Entities;

use App\MediaUpload;
use App\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;

class Category extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = 'categories';

    protected $fillable = ["name","slug","description","image_id","status_id", "sort_order", "show_home", "featured", "banner_id", "m_banner_id"];

    public function image(): HasOne
    {
        return $this->hasOne(MediaUpload::class,"id","image_id");
    }

    public function bannerImage(): HasOne
    {
        return $this->hasOne(MediaUpload::class,"id","banner_id");
    }

    public function mobileBannerImage(): HasOne
    {
        return $this->hasOne(MediaUpload::class,"id","m_banner_id");
    }

    public function status(): HasOne
    {
        return $this->hasOne(Status::class,"id","status_id");
    }

    public function product(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class,ProductCategory::class,"category_id","id","id","product_id");
    }

    public function subcategory(): HasMany
    {
        return $this->hasMany(SubCategory::class,"category_id","id");
    }


    public function childcategory(): HasMany
    {
        return $this->hasMany(ChildCategory::class,"category_id","id");
    }
    
    protected static function newFactory()
    {
        return \Modules\Attributes\Database\factories\CategoryFactory::new();
    }
}
