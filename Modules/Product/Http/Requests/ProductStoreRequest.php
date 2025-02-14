<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            "name" => "required",
            "slug" => [
                "required",
                Rule::unique('products')->ignore($this->id)
            ],
            "summery" => "required",
            "description" => "required",
            "brand" => "nullable",
            "cost" => "required|numeric",
            "price" => "nullable|numeric",
            "sale_price" => "required|numeric",
            "sku" => ["required", ($this->id ?? null) ? Rule::unique("product_inventories")->ignore($this->id,"product_id") :  Rule::unique("product_inventories")],
            "quantity" => "nullable|integer",
            "unit_id" => "required",
            "uom" => "required|numeric",
            "image_id" => "required",
            "product_gallery" => "nullable",
            "tags" => "nullable",
            "badge_id" => "nullable",
            "inventory_details_id" => "nullable",
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
            "item_size.*" => "nullable",
            "item_color.*" => "nullable",
            "item_image.*" => "nullable",
            "item_additional_price.*" => "nullable|numeric",
            "item_extra_price.*" => "nullable|numeric",
            "item_stock_count.*" => "nullable|numeric",
            "item_extra_cost.*" => "nullable|numeric",
            "item_attribute_id.*" => "nullable",
            "item_attribute_name.*" => "nullable",
            "item_attribute_value.*" => "nullable",
            "category_id" => "required",
            "sub_category" => "nullable",
            "child_category" => "nullable",
            "delivery_option" => "nullable",
            "general_title" => "nullable",
            "general_description" => "nullable",
            "general_image" => "nullable",
            "facebook_title" => "nullable",
            "facebook_description" => "nullable",
            "facebook_image" => "nullable",
            "twitter_title" => "nullable",
            "twitter_description" => "nullable",
            "twitter_image" => "nullable",
            "min_purchase" => "nullable|numeric",
            "max_purchase" => "nullable|numeric",
            "is_refundable" => "nullable",
            "is_inventory_warn_able" => "nullable",
            "is_taxable" => "nullable",
            "tax_class_id" => [
                Rule::requiredIf(function () {
                    return $this->is_taxable == 1;
                })
            ],
            "sort_order" => "nullable",
            'height'=> "nullable",
            'width'=> "nullable",
            'weight'=> "nullable",
            'length'=> "nullable",
            "item_height" => "nullable",
            "item_width" => "nullable",
            "item_weight" => "nullable",
            "item_length" => "nullable",
            "cost_usd" => "nullable|numeric|min:0",
            "price_usd" => "nullable|numeric|min:0",
            "sale_price_usd" => "nullable|numeric|min:0",
            "increase_percentage_usd" => "nullable|numeric|min:0",
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            "is_inventory_warn_able" => $this->is_inventory_warning
        ]);
    }

    public function messages(): array
    {
        return [
            "cost.required" => "Cost filed is required for your accounting...",
            "price.required" => "Regular price is required.",
            "sku.required" => "SKU Stock Kipping Unit is required",
            "uni.required" => "Please Select a unit type",
            "uom.required" => "UOM Unit of measurement field is required.",
            "cost_usd.min" => "Base price in USD must be greater than 0.",
            "price_usd.min" => "Regular price in USD must be greater than 0.",
            "sale_price_usd.min" => "Sale price in USD must be greater than 0.",
            "increase_percentage_usd.min" => "Increasing percentage for USD price must be greater than 0.",
            "cost_usd.numeric" => "Base price in USD must be a number.",
            "price_usd.numeric" => "Regular price in USD must be a number.",
            "sale_price_usd.numeric" => "Sale price in USD must be a number.",
            "increase_percentage_usd.numeric" => "Increasing percentage for USD price must be a number.",
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
