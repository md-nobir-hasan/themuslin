<?php

namespace App\Http\Requests\CategoryMenus;

use Illuminate\Foundation\Http\FormRequest;

class Fetch_sub_category_request extends FormRequest
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
    public function rules()
    {
        return [
            "_token" => "required",
            "category_id" => "required"
        ];
    }
}
