<?php

namespace Modules\NewsLetter\Entities;

use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
    protected $table = 'newsletters';
    protected $fillable = ['email','token','verified'];
    
    protected static function newFactory()
    {
        return \Modules\NewsLetter\Database\factories\NewsLetterFactory::new();
    }
}
