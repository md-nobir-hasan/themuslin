<?php

namespace Modules\Wallet\Http\Services;

use App\Http\Services\Commission;
use Modules\Order\Entities\SubOrder;
use Modules\Order\Entities\SubOrderCommission;
use Modules\Product\Entities\Product;
use Modules\Refund\Entities\RefundRequest;
use Modules\Refund\Entities\RefundRequestProduct;
use Modules\Wallet\Entities\Wallet;

class WalletService
{
    /**
     * @param $identity
     * @param $type
     * @return mixed
     */
    public static function createWallet($identity, $type): mixed
    {
        return Wallet::create([
            "user_id" => $type == "user" ? $identity : null ,
            "vendor_id" => $type == "vendor" ? $identity : null,
            "delivery_man_id" => $type == "delivery_man" ? $identity : null,
            "balance" => 0,
            "pending_balance" => 0,
            "status" => 1,
        ]);
    }

    /**
     * @param $orderId
     * @param string $calculation_type
     * @return void
     */
    public static function updateWallet($orderId, string $calculation_type = "plus"): void
    {
        $vendors = SubOrder::where("order_id", $orderId)
            ->where("vendor_id", "!=", null)->get();

        foreach($vendors as $vendor) {
            $total = $vendor->total_amount; // + $vendor->shipping_cost + $vendor->tax_amount;

            // get commission information
            $commission = SubOrderCommission::where("sub_order_id", $vendor->id)->first();

            self::updateVendorWallet($vendor->vendor_id, $total - $commission->commission_amount, plus: $calculation_type);
        }
    }

    /**
     * @param $vendor_id
     * @param $amount
     * @param bool $plus
     * @param string $column
     * @param null $sub_order_id
     * @param null $transaction_id
     * @return false
     */
    public static function updateVendorWallet($vendor_id, $amount, bool $plus = true, string $column = 'pending_balance', $sub_order_id = null,$transaction_id = null): bool
    {
        // first i need to get vendor info if vendor exist on database than fetch the record and after that update the table
        $wallet = Wallet::where("vendor_id",$vendor_id)->first();

        // check wallet is empty or not if empty then create new wallet for this
        if(empty($wallet))
            $wallet = static::createWallet($vendor_id, "vendor");

        //todo:: check some condition before store wallet history
        if($column == 'balance'){
            // add new record on wallet history
            WalletHistoryService::store([
                "wallet_id" => $wallet->id,
                "vendor_id" => $vendor_id,
                "sub_order_id" => $sub_order_id,
                "amount" => $amount,
                "transaction_id" => $transaction_id,
                "type" => $plus ? 1 : 0,
            ]);
        }

        if($plus){
            return $wallet->increment($column,$amount);
        }else{
            return $wallet->decrement($column,$amount);
        }
    }

    /**
     * @throws \Exception
     */
    public static function updateDeliveryManWallet($delivery_man_id, $amount, bool $plus = true, string $column = 'pending_balance', $order_id = null,$transaction_id = null): bool
    {
        // first i need to get vendor info if vendor exists on a database than fetch the record and after that update the table
        $wallet = Wallet::where("delivery_man_id", $delivery_man_id)->first();
        // check wallet is empty or not if empty then create a new wallet for this
        if(empty($wallet)){
            $wallet = static::createWallet($delivery_man_id, "delivery_man");
        }

        //todo:: check some condition before store wallet history
        if($column == 'balance'){
            // add new record on wallet history
            WalletDeliveryManService::storeHistory([
                "wallet_id" => $wallet->id,
                "delivery_man_id" => $delivery_man_id,
                "order_id" => $order_id,
                "amount" => $amount,
                "transaction_id" => $transaction_id,
                "type" => $plus ? 1 : 0,
            ]);
        }

        if($plus){
            return $wallet->increment($column,$amount);
        }else{
            return $wallet->decrement($column,$amount);
        }
    }

    /**
     * @param $user_id
     * @param $amount
     * @param bool $plus
     * @param string $column
     * @param null $sub_order_id
     * @param null $transaction_id
     * @return false
     * @throws \Exception
     */
    public static function updateUserWallet($user_id, $amount, bool $plus = true, string $column = 'pending_balance', $sub_order_id = null,$transaction_id = null, $createHistory = true, $checkBalance = false, $historyType = null): bool
    {
        // first i need to get vendor info if vendor exist on database than fetch the record and after that update the table
        $wallet = Wallet::where("user_id",$user_id)->first();

        // check balance
        if ($checkBalance){
            if(!($checkBalance && $wallet->$column >= $amount)){
                throw new \Exception("Insufficient wallet balance please deposit and try again latter.",999);
            }
        }

        //todo:: check some condition before store wallet history
        if($column == 'balance' && $createHistory){
            // add new record on wallet history
            WalletHistoryService::store([
                "wallet_id" => $wallet->id,
                "vendor_id" => null,
                "user_id" => $user_id,
                "sub_order_id" => $sub_order_id,
                "amount" => $amount,
                "transaction_id" => $transaction_id,
                "type" => $historyType ?: ($plus ? 1 : 0),
            ]);
        }

        if($plus){
            return $wallet->increment($column,$amount);
        }else{
            return $wallet->decrement($column,$amount);
        }
    }

    /**
     * @param $id
     * @param null $type
     * @return Wallet|null
     */
    public static function findWallet($id, $type = null): ?Wallet
    {
        $type = match($type){
            "vendor" => "vendor_id",
            default => "user_id",
        };

        return Wallet::where($type, $id)->first();
    }

    public static function completeOrderAmount($order_id): bool
    {
        $vendors = SubOrder::with("commission:id,sub_order_id,commission_amount")->where("order_id", $order_id)
            ->where("vendor_id", "!=", null)->get();

        foreach($vendors as $subOrder) {
            $total = $subOrder->total_amount;
            // get commission information
            $commission = $subOrder->commission;
            $amount = $total - ($commission?->commission_amount ?? 0);

            self::updateVendorWallet($subOrder->vendor_id,$amount ,column: 'balance',sub_order_id: $subOrder->id);
            self::updateVendorWallet($subOrder->vendor_id, $amount, plus: false);
        }

        return true;
    }

    public static function updateRefundRequest(float|int $totalAmount,RefundRequest $refundRequest): void
    {
        // check wallet if wallet exists if doesn't exist then create new wallet
        if(!self::findWallet($refundRequest->user_id, "user")) {
            // create wallet for this Requested User
            self::createWallet($refundRequest->user_id, "user");
        }

        // update wallet balance
        // create new row wallet history record
        self::updateUserWallet($refundRequest->user_id,$totalAmount,true,"balance");
    }

    public static function updateVendorWalletFromRefund(RefundRequest $refundRequest){
        // get all requested product for refund
        // group them into vendor id then calculate there order amount with ordered amount
        // decrease order amount from vendor wallet balance
        $allRefundProducts = RefundRequestProduct::with('product')
            ->whereRelation('product','vendor_id','!=',null)
            ->where("refund_request_id", $refundRequest->id)->get();

        foreach ($allRefundProducts->groupBy('product.vendor_id') ?? [] as $vendor_id => $vendorItems){
            $amount = 0;
            foreach($vendorItems ?? [] as $item){
                $amount += $item->amount * $item->quantity;
            }

            self::updateVendorWallet($vendor_id,$amount,false,'balance');
        }

    }
}
