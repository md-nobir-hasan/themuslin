<?php

namespace Modules\Campaign\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Modules\Campaign\Entities\CampaignSoldProduct
 *
 * @property int $id
 * @property int $product_id
 * @property int $sold_count
 * @property float $total_amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CampaignSoldProduct newModelQuery()
 * @method static Builder|CampaignSoldProduct newQuery()
 * @method static Builder|CampaignSoldProduct query()
 * @method static Builder|CampaignSoldProduct whereCreatedAt($value)
 * @method static Builder|CampaignSoldProduct whereId($value)
 * @method static Builder|CampaignSoldProduct whereProductId($value)
 * @method static Builder|CampaignSoldProduct whereSoldCount($value)
 * @method static Builder|CampaignSoldProduct whereTotalAmount($value)
 * @method static Builder|CampaignSoldProduct whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CampaignSoldProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sold_count',
        'total_amount',
    ];
}
