<?php

namespace Modules\Wallet\Services;

use App\Http\Services\NotificationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;

class VendorWithdrawRequestNotificationService extends NotificationService
{
    public function generateMessage($type): array|string|Translator|Application|null
    {
        return match($type){
            "pending" => __("New withdraw request is sent by a vendor"),
            "completed" => __("admin has completed your your withdraw request"),
            "processing" => __("admin has changed your your withdraw request status pending to progress"),
            "failed" => __("Your withdraw request is failed to complete please contact with admin"),
            "refunded" => __("admin has refunded your your withdraw request"),
            "cancelled" => __("Your request has been cancelled by admin"),
        };
    }
}