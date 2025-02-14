<?php

namespace Modules\Wallet\Http\Services;

use Modules\Wallet\WalletDeliveryManHistory;

class WalletDeliveryManService
{
    public static function storeHistory($data){
        return WalletDeliveryManHistory::create([
            "wallet_id" => $data["wallet_id"] ?? null,
            "order_id" => $data["order_id"] ?? null,
            "amount" => $data["amount"] ?? null,
            "transaction_id" => $data["transaction_id"] ?? null,
            "type" => $data["type"] ?? null,
        ]);
    }
}