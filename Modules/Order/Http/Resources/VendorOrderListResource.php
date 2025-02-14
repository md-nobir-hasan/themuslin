<?php

namespace Modules\Order\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorOrderListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "order_status" => $this->orderTrack->first()->name ?? "pending",
            "payment_status" => $this->order->payment_status,
            "total_amount" => $this->total_amount,
            "created_at" => $this->created_at->format("Y-m-d h:i:s"),
        ];
    }
}
