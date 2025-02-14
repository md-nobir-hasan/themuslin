<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StaticOption
 *
 * @property int $id
 * @property string $option_name
 * @property string|null $option_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StaticOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaticOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaticOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|StaticOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticOption whereOptionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticOption whereOptionValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticOption whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaticOption extends Model
{
    protected $table = 'static_options';
    protected $fillable = ['option_name','option_value'];
}
