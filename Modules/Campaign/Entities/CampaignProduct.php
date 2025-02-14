<?php

namespace Modules\Campaign\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Campaign\Entities\CampaignProduct
 *
 * @property int $id
 * @property int $product_id
 * @property int $campaign_id
 * @property string $campaign_price
 * @property int $units_for_sale
 * @property string|null $start_date
 * @property string|null $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Campaign\Entities\Campaign|null $campaign
 * @property-read Product|null $product
 * @method static Builder|CampaignProduct newModelQuery()
 * @method static Builder|CampaignProduct newQuery()
 * @method static Builder|CampaignProduct query()
 * @method static Builder|CampaignProduct whereCampaignId($value)
 * @method static Builder|CampaignProduct whereCampaignPrice($value)
 * @method static Builder|CampaignProduct whereCreatedAt($value)
 * @method static Builder|CampaignProduct whereEndDate($value)
 * @method static Builder|CampaignProduct whereId($value)
 * @method static Builder|CampaignProduct whereProductId($value)
 * @method static Builder|CampaignProduct whereStartDate($value)
 * @method static Builder|CampaignProduct whereUnitsForSale($value)
 * @method static Builder|CampaignProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CampaignProduct extends Model
{
    protected $fillable = [
        'campaign_id',
        'product_id',
        'campaign_price',
        'units_for_sale',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        "end_date" => "datetime",
        "start_date" => "datetime"
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->with("inventory");
    }

    public function campaign(): BelongsTo
    {
        // run a condition for expire campaign if campaign run on expired time then this campaign will not found on frontend
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id')
            ->where("campaigns.end_date","<", Carbon::now());
    }
}
