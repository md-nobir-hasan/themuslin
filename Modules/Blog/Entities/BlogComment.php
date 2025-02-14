<?php

namespace Modules\Blog\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = ['blog_id','user_id','commented_by','comment_content','parent_id','type','parent_id', 'status'];
    protected $table = 'blog_comments';

    public function blog(){
        return $this->belongsTo(Blog::class,'blog_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function reply () {
        return $this->hasMany(BlogComment::class, 'parent_id', 'id');
    }
}
