<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ImageGalleryCategory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ImageGalleryCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageGalleryCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageGalleryCategory query()
 * @mixin \Eloquent
 */
class ImageGalleryCategory extends Model
{
    protected $table = 'image_gallery_categories';
    protected $fillable = ['title','status'];
}
