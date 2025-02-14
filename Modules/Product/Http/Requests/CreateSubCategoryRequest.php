<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSubCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ["required",'string',"max:191",Rule::unique("product_sub_categories")],
            "slug" => ["required",'string',"max:191",Rule::unique("product_sub_categories")],
            "status" => ["required"],
            "image" => "required",
            "category_id" => ["required"],
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
