<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SocialIcons
 *
 * @property int $id
 * @property string $icon
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SocialIcons newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialIcons newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialIcons query()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialIcons whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialIcons whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialIcons whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialIcons whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialIcons whereUrl($value)
 * @mixin \Eloquent
 */
class SocialIcons extends Model
{
    protected $table = 'social_icons';
    protected $fillable = ['icon','url'];
}
