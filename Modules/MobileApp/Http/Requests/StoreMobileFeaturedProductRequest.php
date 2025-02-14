<?php

namespace Modules\MobileApp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class StoreMobileFeaturedProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(["type" => "string", "ids" => "string"])]
    public function rules() : array
    {
        return [
            "type" => "required",
            "ids" => "required"
        ];
    }

    protected function prepareForValidation()
    {
        $ids = $this->category ? $this->featured_category : $this->featured_product;

        return $this->merge([
            "type" => $this->category ? "category" : "product",
            "ids" => json_encode($ids),
        ]);
    }
}
