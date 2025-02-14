<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Http;
use Log;
use Modules\Vendor\Entities\Vendor;

class XGNotification extends Model
{
    protected $fillable = [
        'vendor_id',
        'user_id',
        'model',
        'model_id',
        'message',
        'type',
        'is_read_admin',
        'is_read_vendor',
        'is_read_user',
        'delivery_man_id',
        'is_read_delivery_man'
    ];

    public function notifiable(): MorphTo
    {
        return $this->morphTo('model','model_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class,"vendor_id","id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\Modules\User\Entities\User::class,"user_id","id");
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modal){
            // here check vendor id is not empty
            if($modal->vendor_id){
                // send notification to  vendor app
                $vendor = Vendor::find($modal->vendor_id);

                $notificationBody = [
                    'title' => __('New :message notification', [
                        "message" => ucfirst(str_replace("sub_order","order", $modal->message))
                    ]),
                    'id' => $modal->model_id,
                    'body' => $modal->message,
                    'description' => '',
                    'type' => $modal->type,
                    'sound' => 'default',
                    'fcm_device' => ''
                ];

                $notification = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=' . get_static_option('vendor_firebase_server_key'),
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'message' => [
                        'body' => 'subject',
                        'title' => 'title',
                    ],
                    'priority' => 'high',
                    'data' => $notificationBody,
                    'to' => $vendor->firebase_device_token,
                ]);

                Log::info($notification);
            }
        });
    }
}
