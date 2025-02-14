<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "product_id" => "required",
            "sku" => "required|unique:product_inventories,product_id," . $this->product_id ?? 0,
            "quantity" => "nullable",
            "unit_id" => "required",
            "uom" => "required",
            "item_size" => "nullable",
            "item_color" => "nullable",
            "item_image" => "nullable",
            "item_additional_price" => "nullable",
            "item_extra_price" => "nullable",
            "item_stock_count" => "nullable",
            "item_extra_cost" => "nullable",
            "item_attribute_id" => "nullable",
            "item_attribute_name" => "nullable",
            "item_attribute_value" => "nullable",
        ];
    }

    public function messages(): array
    {
        return [
            "price.required" => "Regular price is required.",
            "sku.required" => "SKU Stock Kipping Unit is required",
            "uni.required" => "Please Select a unit type",
            "uom.required" => "UOM Unit of measurement field is required."
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
