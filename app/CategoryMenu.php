<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\CategoryMenu
 *
 * @property int $id
 * @property string $title
 * @property string|null $content
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryMenu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryMenu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryMenu query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryMenu whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryMenu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryMenu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryMenu whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryMenu whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryMenu whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CategoryMenu extends Model
{
    use HasFactory;
    protected $table = 'category_menus';
    protected $fillable = ['title','content','status'];
}
