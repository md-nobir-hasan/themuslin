<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\KeyFeatures
 *
 * @property int $id
 * @property string $title
 * @property string $icon
 * @property string|null $lang
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|KeyFeatures newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KeyFeatures newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KeyFeatures query()
 * @method static \Illuminate\Database\Eloquent\Builder|KeyFeatures whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KeyFeatures whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KeyFeatures whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KeyFeatures whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KeyFeatures whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KeyFeatures whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class KeyFeatures extends Model
{
    protected $table = 'key_features';
    protected $fillable = ['title','subtitle','icon','description','image','features','lang'];
   
}
