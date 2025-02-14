<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "id" => "required",
            "name" => ["required",'string',"max:191",Rule::unique("product_sub_categories")->ignore($this->id)],
            "slug" => ["required",'string',"max:191",Rule::unique("product_sub_categories")->ignore($this->id)],
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
