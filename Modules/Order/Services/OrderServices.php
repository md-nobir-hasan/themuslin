<?php

namespace Modules\Order\Services;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Modules\Order\Entities\SubOrder;
use Modules\Order\Traits\OrderTrack;
use Modules\Order\Traits\StoreOrderTrait;

class OrderServices
{
    use StoreOrderTrait, OrderTrack;
}