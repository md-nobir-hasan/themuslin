<?php

namespace Modules\Product\Observers;

use Modules\Product\Entities\Product;
use Modules\Product\Services\ProductNotificationService;

class ProductObserver
{
    public function created(Product $product): void
    {
        ProductNotificationService::init()
            ->setProductName($product->name)
            ->setType("product")
            ->send($product, "created");
    }

    public function updated(Product $product): void
    {
        ProductNotificationService::init()
            ->setProductName($product->name)
            ->setType("product")
            ->send($product, "updated");
    }

    public function deleted(Product $product): void
    {
        ProductNotificationService::init()
            ->setProductName($product->name)
            ->setType("product")
            ->send($product, "deleted");
    }

    public function restored(Product $product): void
    {
        ProductNotificationService::init()
            ->setProductName($product->name)
            ->setType("product")
            ->send($product, "restored");
    }

    public function forceDeleted(Product $product): void
    {
        ProductNotificationService::init()
            ->setProductName($product->name)
            ->setType("product")
            ->send($product, "forceDeleted");
    }
}
