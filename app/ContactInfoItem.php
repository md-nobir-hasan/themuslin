<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ContactInfoItem
 *
 * @property int $id
 * @property string $title
 * @property string $icon
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInfoItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInfoItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInfoItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInfoItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInfoItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInfoItem whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInfoItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInfoItem whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactInfoItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContactInfoItem extends Model
{
    protected $table = 'contact_info_items';
    protected $fillable = [ 'title','icon','description'];
}
