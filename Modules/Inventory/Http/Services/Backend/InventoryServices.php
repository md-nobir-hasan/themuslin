<?php

namespace Modules\Inventory\Http\Services\Backend;

use Modules\Product\Http\Traits\ProductGlobalTrait;

class InventoryServices
{
    use ProductGlobalTrait;

    public function update($data){
        $id = $data["product_id"];
        unset($data["product_id"]);
        return $this->updateInventory($data,$id);
    }
}