<?php

namespace Modules\Order\Observers;

use Modules\Order\Entities\Order;
use Modules\Order\Services\OrderNotificationService;

class OrderObserver
{
    public function created(Order $order): void
    {
        OrderNotificationService::init($order)
            ->setType("order")
            ->send($order, "created");
    }

    public function updated(Order $order): void
    {

        OrderNotificationService::init($order)
            ->setType("order")
            ->send($order, "updated");
    }
}
