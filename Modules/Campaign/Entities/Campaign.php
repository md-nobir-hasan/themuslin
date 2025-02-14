<?php

namespace Modules\Campaign\Entities;

use Modules\Campaign\Entities\CampaignProduct;
use App\MediaUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Modules\AdminManage\Entities\Admin;
use Modules\Product\Entities\Product;
use Modules\Vendor\Entities\Vendor;

/**
 * Modules\Campaign\Entities
 *
 * @property int $id
 * @property string $title
 * @property string $subtitle
 * @property int $image
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|CampaignProduct[] $products
 * @property-read int|null $products_count
 * @method static Builder|Campaign newModelQuery()
 * @method static Builder|Campaign newQuery()
 * @method static Builder|Campaign query()
 * @method static Builder|Campaign whereCreatedAt($value)
 * @method static Builder|Campaign whereEndDate($value)
 * @method static Builder|Campaign whereId($value)
 * @method static Builder|Campaign whereImage($value)
 * @method static Builder|Campaign whereStartDate($value)
 * @method static Builder|Campaign whereStatus($value)
 * @method static Builder|Campaign whereSubtitle($value)
 * @method static Builder|Campaign whereTitle($value)
 * @method static Builder|Campaign whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'image',
        'status',
        'start_date',
        'end_date',
        'admin_id',
        'vendor_id',
        'type'
    ];

    protected $casts = ['end_date' => 'datetime'];

    public function product(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class,CampaignProduct::class,"campaign_id","id","id","product_id");
    }

    public function campaignImage(): HasOne
    {
        return $this->hasOne(MediaUpload::class,"id","image");
    }

    public function products(): HasMany
    {
        return $this->hasMany(CampaignProduct::class);
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class,"id","admin_id");
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class,"id","vendor_id");
    }

    public function scopeProfile($query){
        return $query->when($this->type == 'admin', function ($q){
            $q->with("admin");
        })->when($this->type == "vendor" , function ($q){
            $q->with("vendor");
        });
    }
}
