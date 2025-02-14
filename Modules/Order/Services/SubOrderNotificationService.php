<?php

namespace Modules\Order\Services;

use App\Http\Services\NotificationService;

class SubOrderNotificationService extends NotificationService
{
    /**
     * @param string $type
     * @return string
     */
    protected function generateMessage(string $type): string
    {
        if($this->model->vendor_id){
            $this->data = [
                "vendor_id" => $this->model->vendor_id
            ];
        }

        return match($type){
            "created" => "New Order Has been placed. [br] Sub order id [b]#" . $this->model->id . "[/b]",
        };
    }
}