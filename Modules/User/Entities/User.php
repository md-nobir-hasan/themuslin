<?php

namespace Modules\User\Entities;

use App\MediaUpload;
use App\Shipping\UserShippingAddress;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\CountryManage\Entities\City;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use Modules\Wallet\Entities\Wallet;
use App\Shipping\ShippingAddress;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use SoftDeletes;

    protected $with = ["profile_image"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'first_name',
        'last_name',
        'password',
        'image',
        'phone',
        'address',
        'country',
        'state',
        'city',
        'zipcode',
        'email_verified',
        'email_verify_token',
        'facebook_id',
        'google_id',
        'check_online_status',
        'firebase_device_token'
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

    public function userCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, "country", "id");
    }

    public function userState(): BelongsTo
    {
        return $this->belongsTo(State::class, "state", "id");
    }

    public function userCity(): BelongsTo
    {
        return $this->belongsTo(City::class, "city", "id");
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'check_online_status' => 'datetime',
        'created_at' => 'datetime'
    ];

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, "id", "user_id");
    }

    public function shipping()
    {
        return $this->hasMany(UserShippingAddress::class);
    }

    public function profile_image(): HasOne
    {
        return $this->hasOne(MediaUpload::class, "id", "image")->select("id", "path", "title", "alt");
    }

    public function shippingAddress()
    {
        return $this->hasOne(ShippingAddress::class, 'user_id')
            ->where('default_shipping_status', 1); // Default shipping address
    }
}
