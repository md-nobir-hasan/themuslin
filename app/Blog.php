<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Blog
 *
 * @property int $id
 * @property string $title
 * @property string $blog_content
 * @property int $blog_categories_id
 * @property string $tags
 * @property string|null $image
 * @property string|null $meta_tags
 * @property string|null $meta_description
 * @property string|null $user_id
 * @property string|null $excerpt
 * @property string|null $og_meta_title
 * @property string|null $og_meta_description
 * @property string|null $og_meta_image
 * @property string|null $slug
 * @property string|null $author
 * @property string|null $status
 * @property int $visit_count
 * @property string|null $meta_title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\BlogCategory|null $category
 * @property-read \App\Admin|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Blog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereBlogCategoriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereBlogContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereMetaTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereOgMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereOgMetaImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereOgMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereVisitCount($value)
 * @mixin \Eloquent
 */
class Blog extends Model
{
    protected $table = 'blogs';
    protected $fillable = [
        'title',
        'status',
        'author',
        'slug',
        'meta_title',
        'meta_description',
        'meta_tags',
        'excerpt',
        'blog_content',
        'blog_categories_id',
        'tags',
        'image',
        'user_id',
        'og_meta_title',
        'og_meta_description',
        'og_meta_image'
    ];

    protected $with = ['category',"blogImage"];

    public function category(){
        return $this->belongsTo('App\BlogCategory','blog_categories_id');
    }
    public function user(){
        return $this->belongsTo('App\Admin','user_id');
    }

    public function blogImage(){
        return $this->hasOne(MediaUpload::class,"id","image");
    }

//    public function toFeedItem() : FeedItem
//    {
//        return FeedItem::create([
//            'id' => $this->id,
//            'title' => $this->title,
//            'summary' => $this->excerpt,
//            'updated' => $this->updated_at,
//            'link' => route('frontend.blog.single',$this->slug),
//            'author' => $this->author,
//        ]);
//    }

    public static function getAllFeedItems()
    {
        return Blog::all();
    }

}
