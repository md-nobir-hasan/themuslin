<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ImageGallery
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ImageGallery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageGallery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageGallery query()
 * @mixin \Eloquent
 */
class ImageGallery extends Model
{
    protected $table = 'image_galleries';
    protected $fillable = ['image','title','cat_id'];
}
