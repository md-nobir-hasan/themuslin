<?php

namespace App;

use App\MediaUpload;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Page
 *
 * @property int $id
 * @property string $title
 * @property string|null $slug
 * @property string|null $meta_tags
 * @property string|null $meta_description
 * @property string|null $content
 * @property string|null $status
 * @property string|null $visibility
 * @property int|null $page_builder_status
 * @property int|null $navbar_category_dropdown_open
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $navbar_variant
 * @property string|null $breadcrumb_status
 * @property string|null $page_container_option
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereBreadcrumbStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereNavbarCategoryDropdownOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereNavbarVariant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page wherePageBuilderStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page wherePageContainerOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereVisibility($value)
 * @mixin \Eloquent
 */
class Page extends Model
{
    protected $table = 'pages';
    protected $fillable = [
        'title',
        'slug',
        'meta_tags',
        'meta_description',
        'content',
        'status',
        'visibility',
        'page_builder_status',
        'page_container_option',
        'navbar_category_dropdown_open',
        'navbar_variant',
        'breadcrumb_status',
        'image_1',
        'image_2',
    ];


    public function images() {

        return $this->hasmany(MediaUpload::class, 'identifier_id', 'id')->where('identifier_type', '=', 'Page')
                                                                        ->orderBy('media_uploads.sort_order', 'asc');
    }


    public function desktopImages() {

        return $this->hasmany(MediaUpload::class, 'identifier_id', 'id')->where('identifier_type', '=', 'Page')
                                                                        ->where('device', 'desktop')
                                                                        ->orderBy('media_uploads.sort_order', 'asc');
    }


    public function mobileImages() {

        return $this->hasmany(MediaUpload::class, 'identifier_id', 'id')->where('identifier_type', '=', 'Page')
                                                                        ->where('device', 'mobile')
                                                                        ->orderBy('media_uploads.sort_order', 'asc');
    }


    public function imageInfo_1()
    {
        return $this->hasOne(MediaUpload::class,"id","image_1");
    }


    public function imageInfo_2()
    {
        return $this->hasOne(MediaUpload::class,"id","image_2");
    }
}
