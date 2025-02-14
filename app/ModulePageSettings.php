<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\ModulePageSettings
 *
 * @property int $id
 * @property string $option_name
 * @property string|null $option_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ModulePageSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModulePageSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModulePageSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModulePageSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModulePageSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModulePageSettings whereOptionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModulePageSettings whereOptionValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModulePageSettings whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ModulePageSettings extends Model
{
    use HasFactory;
    protected $fillable = [
        'option_name',
        'option_value'
    ];
}
