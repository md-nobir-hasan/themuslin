<?php

namespace Modules\MobileApp\Entities;

use App\MediaUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileIntro extends Model
{
    protected $with = ["image"];

    protected $fillable = [
        "title",
        "description",
        "image_id",
        "type"
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(MediaUpload::class,"image_id","id");
    }
}