<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternationalShipping extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "slug",
        "api_key",
        "api_secret",
        "api_url",
        "api_url_test",
        "is_active"
    ];
}
