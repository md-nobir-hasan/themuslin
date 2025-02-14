<?php

namespace Modules\Wallet\Http\Services;


// this will maintain and handle only store and get search paginate search
use Modules\Wallet\Entities\WalletHistory;

class WalletHistoryService
{
    public static function store($data){
        // this method will store wallet history
        return WalletHistory::create([
            "wallet_id" => $data["wallet_id"],
            "user_id" => $data["user_id"] ?? null,
            "vendor_id" => $data["vendor_id"] ?? null,
            "sub_order_id" => $data["sub_order_id"] ?? null,
            "amount" => $data["amount"] ?? null,
            "transaction_id" => $data["transaction_id"] ?? null,
            "type" => $data["type"] ?? null,
        ]);
    }

    public static function get(){
        // this method will return all history
    }

    public function paginate(){
        // this method will take responsibility for pagination
    }

    public function search(){
        // this method search vendor
    }
}