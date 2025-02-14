<?php

namespace Modules\Order\Services;

use App\Http\Services\NotificationService;
use Modules\Order\Entities\OrderPaymentMeta;

class OrderNotificationService extends NotificationService
{
    /**
     * @param string $type
     * @return string
     */
    protected function generateMessage(string $type): string
    {
        // check type and decide sent message text
        return match($type){
            "created" => "New Order Has been placed. [br] Order id [b]#" . $this->model->id . "[/b]",
            "updated" => "{order_id} This order has been updated",
        };
    }
}