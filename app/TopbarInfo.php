<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\TopbarInfo
 *
 * @property int $id
 * @property string $icon
 * @property string $details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TopbarInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopbarInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopbarInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|TopbarInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopbarInfo whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopbarInfo whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopbarInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopbarInfo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TopbarInfo extends Model
{
    use HasFactory;
    protected $table ='topbar_infos';
    protected $fillable = ['icon','details'];
}
