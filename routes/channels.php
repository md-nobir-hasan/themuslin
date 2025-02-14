<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


use Modules\Chat\Broadcasting\LivechatVendorChannel;

Broadcast::channel('livechat-user-channel.{vendor_id}.{user_id}', function ($user, $vendor_id){
    return (int) $user->id === (int) $vendor_id;
});

Broadcast::channel('livechat-vendor-channel.{user_id}.{vendor_id}', function ($user,$user_id){
    return (int) $user->id === (int) $user_id;
});