<?php

namespace Modules\Attributes\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ["tag_text"];

    protected static function newFactory()
    {
        return \Modules\Attributes\Database\factories\TagFactory::new();
    }
}
