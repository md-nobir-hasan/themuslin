<?php

namespace Modules\Vendor\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawRequestListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "amount" => $this->amount,
            "gateway_id" => $this->gateway_id,
            "vendor_id" => $this->vendor_id,
            "request_status" => $this->request_status,
            "note" => $this->note,
            "image" => $this->image ? asset("assets/uploads/wallet-withdraw-request/" . $this->image) : null,
        ];
    }
}
