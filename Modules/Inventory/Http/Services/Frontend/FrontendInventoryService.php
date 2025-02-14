<?php

namespace Modules\Inventory\Http\Services\Frontend;

use Exception;
use Illuminate\Support\Facades\Mail;
use Modules\Campaign\Entities\CampaignSoldProduct;
use Modules\Inventory\Emails\StockOutEmail;
use Modules\Order\Entities\SubOrderItem;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;
use Modules\Product\Services\ProductNotificationService;

class FrontendInventoryService
{

    private static function updateCampaignSoldProduct($product): void
    {
        $sold_count = CampaignSoldProduct::where('product_id', $product->product_id)->first();

        if (empty($sold_count))
        {
            CampaignSoldProduct::create([
                'product_id' => $product->product_id,
                'sold_count' => 1,
                'total_amount' => $product->campaignProduct->campaign_price,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            if ($sold_count->sold_count < $product->campaignProduct->units_for_sale)
            {
                if ($product->campaignProduct->units_for_sale >= ($product->quantity + $sold_count->sold_count))
                {
                    $sold_count->increment('sold_count', $product->quantity);
                    $sold_count->total_amount += $product->campaignProduct->campaign_price*$product->quantity;
                    $sold_count->save();
                } else {
                    back()->withErrors('Campaign sell limitation is over, You can not purchase current amount');
                }
            } else {
                back()->withErrors('Campaign sell limitation is over, You can not purchase this product right now');
            }
        }
    }

    /**
     * @throws Exception
     */
    private static function updateProductInventory($product): void
    {
        if ($product->variant_id !== null) {
            $variants = ProductInventoryDetail::where(['product_id' => $product->product_id, 'id' => $product->variant_id])->get();
            if (!empty($variants)) {
                foreach ($variants ?? [] as $variant) {
                    $variant->decrement('stock_count', $product->quantity);
                    $variant->increment('sold_count', $product->quantity);
                }
            }
        }

        $product_inventory = ProductInventory::where('product_id', $product->product_id)->first();
        if ($product_inventory){
            if($product_inventory->stock_count >= $product->quantity){
                $product_inventory->decrement('stock_count', $product->quantity);
                $product_inventory->sold_count = $product_inventory->sold_count == null ? 1 : $product_inventory->sold_count + $product->quantity;
                $product_inventory->save();
            } else {
                throw new Exception($product->name .' '. __("This product is Stock out please remove it from your cart and try again"));
            }
        }
    }

    /**
     * @param int $order_id
     * @return void
     * @throws Exception
     */
    public static function updateInventory(int $order_id): void // getting all parameters in one array
    {
        $ordered_products = SubOrderItem::with("campaignProduct")->where('order_id', $order_id)->get();

        foreach ($ordered_products ?? [] as $product) {
            // check this product is belongs to product table is exist product into product table then call campaignSoldProduct method
            // now call updateCampaignSoldProduct this for updating campaignsoldproduct

            if(!empty($product->campaignProduct))
            {
                self::updateCampaignSoldProduct($product);
            }

            // now call updateProductInventory this method for updating product inventory
            self::updateProductInventory($product);
        }

        // check stock if stock is low then send email
        self::checkStock(); // Checking Stock for warning and email notification
    }

    private static function checkStock(): void
    {
        // Inventory Warnings
        $threshold_amount = get_static_option('stock_threshold_amount') ?? 10;

        $inventory_product_items = ProductInventoryDetail::where('stock_count', '<=', $threshold_amount)
            ->whereHas('product', function ($query) {
                $query->where('is_inventory_warn_able', 1);
            })
            ->select('id', 'product_id')
            ->get();

        $inventory_product_items_id = !empty($inventory_product_items) ? $inventory_product_items->pluck('product_id')->toArray() : [];

        $products = Product::with('inventory')
            ->where('is_inventory_warn_able', 1)
            ->whereHas('inventory', function ($query) use ($threshold_amount) {
                $query->where('stock_count', '<=', $threshold_amount);
            })
            ->select('id')
            ->get();

        $products_id = !empty($products) ? $products->pluck('id')->toArray() : [];

        $every_filtered_product_id = array_unique(array_merge($inventory_product_items_id, $products_id));
        $all_products = Product::with("inventory","inventoryDetail")
            ->whereIn('id', $every_filtered_product_id)
            ->select('id', 'name', 'is_inventory_warn_able','vendor_id')
            ->get();

        foreach ($all_products as $item) {
            $inventory = $item?->inventory?->stock_count;
            $variant = $item->inventoryDetail->where('stock_count', '<=', $threshold_amount)->first();
            $variant = !empty($variant) ? $variant->stock_count : [];

            $stock = min($inventory, $variant);
            $item->stock = $stock;

            // send notification as a warning in admin panel and vendor panel
            $productNotification = ProductNotificationService::init($item?->inventory)
                ->setType('stock_out')
                ->setProductName($item->name)
                ->setStockCount($item->stock);

            if($item->vendor_id)
                $productNotification->setVendor($item->vendor_id);

            $productNotification->send($item?->inventory, "stock_out");
        }

        $email = get_static_option('order_receiving_email') ?? get_static_option('site_global_email');

        try {
            Mail::to($email)->send(new StockOutEmail($all_products));

        }catch (Exception $e){
            return;
        }

    }
}