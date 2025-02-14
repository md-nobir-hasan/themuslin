<?php

namespace Modules\Blog\Entities;

use App\MediaUpload;
use Illuminate\Database\Eloquent\Model;
use Modules\AdminManage\Entities\Admin;
use Modules\User\Entities\User;
use Spatie\Translatable\HasTranslations;

class Blog extends Model
{

    protected $table = 'blogs';
    protected $fillable = ['category_id',
        'user_id','admin_id','title','slug','blog_content',
        'image','author','excerpt','status',
        'image_gallery','views','video_url',
        'visibility','featured','created_by','tags'
    ];


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }

    public function category(){
        return $this->belongsTo(BlogCategory::class,'blog_categories_id','id');
    }

    public function metainfo(){
        return $this->morphOne(MetaInfo::class,'metainfoable');
    }

    public function author_data(){
        if ($this->attributes['created_by'] === 'user'){
            return User::find($this->attributes['user_id']);
        }
        return Admin::find($this->attributes['admin_id']);
    }

    public function comments(){
        return $this->hasMany(BlogComment::class,'blog_id','id');
    }


    public function images() {

        return $this->hasmany(MediaUpload::class, 'identifier_id', 'id')->where('identifier_type', '=', 'Blog')
                                                                        ->orderBy('media_uploads.sort_order', 'asc');
    }


    protected static function newFactory()
    {
        return \Modules\Blog\Database\factories\BlogFactory::new();
    }
}
