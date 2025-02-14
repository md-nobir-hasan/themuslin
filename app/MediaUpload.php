<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MediaUpload
 *
 * @property int $id
 * @property string $title
 * @property string $path
 * @property string|null $alt
 * @property string|null $size
 * @property string|null $dimensions
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MediaUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaUpload whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaUpload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaUpload whereDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaUpload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaUpload wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaUpload whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaUpload whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaUpload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaUpload whereUserId($value)
 * @mixin \Eloquent
 */
class MediaUpload extends Model
{
    protected $table = "media_uploads";
    protected $fillable = ['title','alt','size','path','dimensions','user_id','vendor_id','identifier_type','identifier_id','sort_order','device', 'title_text'];
}
