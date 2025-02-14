<?php

namespace Modules\Vendor\Entities;

use App\MediaUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Vendor\Database\factories\VendorShopInfoFactory;

class VendorShopInfo extends Model
{
    use HasFactory;

    protected $with = ["logo","cover_photo"];

    protected $fillable = ["vendor_id","location","number","email","facebook_url","website_url","logo_id","cover_photo_id"];

    public function logo(): HasOne
    {
        return $this->hasOne(MediaUpload::class,"id","logo_id")->select("id","path","alt");
    }

    public function cover_photo(): HasOne
    {
        return $this->hasOne(MediaUpload::class,"id","cover_photo_id")->select("id","path","alt");
    }

    protected static function newFactory()
    {
        return VendorShopInfoFactory::new();
    }
}
