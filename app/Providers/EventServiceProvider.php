<?php

namespace App\Providers;

use App\Events\SendMessage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\ProductOrdered;
use App\Events\SupportMessage;
use App\Listeners\ProductOrderDBUpdate;
use App\Listeners\ProductOrderMailUser;
use App\Listeners\SupportSendMailToAdmin;
use App\Listeners\SupportSendMailToUser;
use Modules\DeliveryMan\Events\AssignDeliveryManEmail;
use Modules\DeliveryMan\Listeners\AssignDeliveryManPushNotificationListener;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ProductOrdered::class => [
            ProductOrderDBUpdate::class,
            ProductOrderMailUser::class,
        ],
        SupportMessage::class => [
            SupportSendMailToAdmin::class,
            SupportSendMailToUser::class,
        ],
//        SendMessage::class,
        AssignDeliveryManEmail::class => [
            AssignDeliveryManPushNotificationListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
