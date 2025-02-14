<?php

namespace App\Listeners;

use Modules\Campaign\Entities\CampaignSoldProduct;
use App\Events\ProductOrdered;
use App\Helpers\CartHelper;
use Modules\Product\Entities\InventoryDetailsAttribute;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetails;
use Modules\Product\Entities\ProductSellInfo;
use DB;
use Throwable;

class ProductOrderDBUpdate
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ProductOrdered $event
     * @return void
     * @throws Throwable
     */
    public function handle(ProductOrdered $event)
    {
        $orders = $event->data;
        if (!isset($orders['order_id']) && !isset($orders['transaction_id'])) return;

        CartHelper::clear();

        try {
            DB::beginTransaction();
            $order_info = ProductSellInfo::find($orders['order_id']);
            $order_details = json_decode($order_info->order_details, true) ?? [];

            if($order_info->payment_gateway == 'cash_on_delivery' || $order_info->payment_gateway == 'bank_transfer' || $order_info->payment_gateway == 'cheque_payment'){
                // set order as complete
                $order_info->update([
                    'transaction_id' => $orders['transaction_id'] ?? $order_info->transaction_id,
                    'payment_status' => 'pending',
                    'status' => 'pending'
                ]);
            }else{
                // set order as complete
                $order_info->update([
                    'transaction_id' => $orders['transaction_id'] ?? $order_info->transaction_id,
                    'payment_status' => 'complete',
                    'status' => 'pending'
                ]);
            }


            // set stocks info
            if (!empty($order_details)) {
                foreach ($order_details as $id => $products) {
                    foreach ($products as $product) {
                        // if order has campaign items, (1) subtract from campaign stock and (2) insert in campaign sell table
                        if (isset($product['attributes']['type']) && $product['attributes']['type'] == 'Campaign Product') {
                            $campaign_sell = CampaignSoldProduct::where('product_id', $product['id'])->first();
                            if ($campaign_sell) {
                                $campaign_sell->update([
                                    'product_id' => $product['id'],
                                    'sold_count' => $campaign_sell->sold_count + $product['quantity'],
                                    'total_amount' => $campaign_sell->total_amount + $product['quantity'] * $product['attributes']['price'],
                                ]);
                            }
                        }

                        $attribute_type = "";
                        $attribute_value = "";
                        $item_attributes = $product['attributes'] ?? [];
                        $inventory_details_id = null;

                        // remove non-attribute data
                        unset($item_attributes['price']);
                        unset($item_attributes['type']);

                        // find ProductInventoryDetails from product attributes
                        if (count($item_attributes)) {
                            $inventory_details_attribute = InventoryDetailsAttribute::where('product_id', $id)
                                ->whereIn('attribute_name', array_keys($item_attributes))
                                ->whereIn('attribute_value', array_values($item_attributes))
                                ->get();

                            $inventory_details_attribute = $inventory_details_attribute->pluck('inventory_details_id')->toArray();
                            $inventory_details_id = !empty($inventory_details_attribute) ? $inventory_details_attribute[0] : null;

                            // update ProductInventoryDetails stock info
                            $inventory_details = ProductInventoryDetails::where('id', $inventory_details_id)->first();

                            if (!is_null($inventory_details)) {
                                ProductInventoryDetails::where('id', $inventory_details_id)->update([
                                    'stock_count' => $inventory_details->stock_count - $product['quantity'],
                                    'sold_count' => $inventory_details->sold_count + $product['quantity'],
                                ]);
                            }
                        }

                        $inventory = ProductInventory::where('product_id', $id)->first();

                        ProductInventory::where('product_id', $id)->update([
                            'stock_count' => $inventory->stock_count - $product['quantity'],
                            'sold_count' => $inventory->sold_count + $product['quantity'],
                        ]);
                    }
                }
            }
            
            DB::commit();
        } catch (Throwable $th) {
            \Log::critical(json_encode([
                'SOURCE' => 'App\Listeners\ProductOrderDBUpdate',
                'MSG' => $th->getMessage(),
                'LINE' => $th->getLine()
            ]));
            DB::rollBack();
        }
    }
}
