<?php

namespace Modules\Product\Http\Services\Admin;

use Modules\Product\Entities\Product;
use Modules\Product\Http\Traits\ProductGlobalTrait;
use Modules\Product\Http\Traits\ProductSearchTrait;

class AdminProductServices
{
    use ProductGlobalTrait, ProductSearchTrait;

    public function store($data): string
    {
        /// store product
        return $this->productStore($data);
    }

    public function update($data, $id){
        return $this->productUpdate($data, $id);
    }

    public function get_edit_product($id, $type = "single"){
        return $this->get_product($id,$type);
    }

    public function delete(int $id): ?bool
    {
        return $this->destroy($id);
    }

    public function clone($id){
        return $this->productClone($id);
    }

    public function bulk_delete_action(array $ids): bool
    {
        return $this->bulk_delete($ids);
    }

    public function trash_delete(int $id): bool
    {
        return $this->trash_destroy($id);
    }

    public function trash_bulk_delete_action(array $ids): bool
    {
        return $this->trash_bulk_delete($ids);
    }
}