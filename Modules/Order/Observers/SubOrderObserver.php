<?php

namespace Modules\Order\Observers;

use Modules\Order\Entities\SubOrder;
use Modules\Order\Services\SubOrderNotificationService;

class SubOrderObserver
{
    public function created(SubOrder $subOrder): void
    {
        SubOrderNotificationService::init($subOrder)
            ->setType("sub_order")
            ->send($subOrder, 'created');
    }

    public function updated(SubOrder $subOrder): void
    {
    }

    public function deleted(SubOrder $subOrder): void
    {
    }

    public function restored(SubOrder $subOrder): void
    {
    }

    public function forceDeleted(SubOrder $subOrder): void
    {
    }
}
