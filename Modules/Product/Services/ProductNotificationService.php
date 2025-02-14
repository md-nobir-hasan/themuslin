<?php

namespace Modules\Product\Services;

use App\Http\Services\NotificationService;

class ProductNotificationService extends NotificationService
{
    private string $productName = "";
    private int $stockCount = 0;

    public function setProductName(string $name): static
    {
        $this->productName = $name;

        return $this;
    }

    public function setStockCount(int $stockCount):static
    {
        $this->stockCount = $stockCount;

        return  $this;
    }

    protected function generateMessage(string $type): string
    {
        // check type and decide sent message text
        return match($type){
            "created" => __("New product has been created") . " {vendor_text} [br][b] " . $this->productName . '[/b]',
            "updated" => "[b]" . $this->productName. ' ' . "[/b][br] ". __("This product has been updated") ." {vendor_text}",
            "deleted" => "[b]" . $this->productName. ' ' . "[/b][br] ". __("This product has store in trash") ." {vendor_text}",
            "restored" => "[b]" . $this->productName. ' ' . "[/b][br] ". __("This product has restored") ." {vendor_text}",
            "forceDeleted" => "[b]" . $this->productName. ' ' . "[/b][br] ". __("This product has deleted permanently") ." {vendor_text}",
            "stock_out" => "[b]" . $this->productName . "[/b][br] ". __("This product is going to out of stock") ." [br] [b] " . __("Current product stock is") . $this->stockCount . '[/b]',
        };
    }
}