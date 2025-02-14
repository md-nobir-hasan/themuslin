<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MetaData extends Model
{
    use HasFactory;
    protected $table = 'meta_data';

    protected $with = ["facebookImage","twitterImage"];

    protected $fillable = [
        'meta_taggable_id',
        'meta_taggable_type',
        'meta_title',
        'meta_tags',
        'meta_description',
        'facebook_meta_tags',
        'facebook_meta_description',
        'facebook_meta_image',
        'twitter_meta_tags',
        'twitter_meta_description',
        'twitter_meta_image'
    ];

    public function facebookImage(): HasOne
    {
        return $this->hasOne(MediaUpload::class,"id","facebook_meta_image");
    }

    public function twitterImage() : HasOne
    {
        return $this->hasOne(MediaUpload::class,"id","twitter_meta_image");
    }

    public function meta_taggable() : MorphTo
    {
        return $this->morphTo();
    }
}
