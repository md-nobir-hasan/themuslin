<?php

namespace Modules\AdminManage\Entities;

use App\MediaUpload;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable, HasRoles;
    protected $with = ["profile_image"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'image',
        'role',
        'password',
        'username',
        'email_verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function profile_image(){
        return $this->hasOne(MediaUpload::class,'id','image');
    }
    
    protected static function newFactory()
    {
        return \Modules\AdminManage\Database\factories\AdminFactory::new();
    }
}
