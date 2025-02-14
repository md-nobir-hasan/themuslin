<?php

namespace Modules\Vendor\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVendorRequest extends FormRequest
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
            "owner_name" => "required",
            "username" => ["required",Rule::unique('vendors')->ignore($this->id)],
            "business_name" => "required",
            "business_type_id" => "required",
            "description" => "nullable",
            "logo_id" => "nullable",
            "cover_photo_id" => "nullable",
            "country_id" => "required",
            "state_id" => "nullable",
            "city_id" => "nullable",
            "zip_code" => "nullable",
            "address" => "nullable",
            "location" => "nullable",
            "email" => "required",
            "number" => "nullable",
            "facebook_url" => "nullable",
            "website_url" => "nullable",
            "bank_name" => "nullable",
            "bank_email" => "nullable",
            "bank_code" => "nullable",
            "account_number" => "nullable"
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
