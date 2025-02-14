<?php

namespace Modules\Order\Traits;

trait OrderTrack
{
    public static function orderTrackArray(): array
    {
        return [
            "ordered",
            "picked_by_courier",
            "on_the_way",
            "ready_for_pickup",
            "delivered",
        ];
    }
}