<?php

namespace Modules\Vendor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRegistrationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "owner_name" => "required",
            "username" => "required|unique:vendors",
            "password" => "required|confirmed|min:6",
            "business_name" => "required",
            "business_type_id" => "required",
            "description" => "nullable",
            "status_id" => "required",
            "email" => "required|unique:vendors",
            "agree_terms" => "required"
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            "business_type_id" => $this->business_type,
            "description" => $this->message,
            "status_id" => 2
        ]);
    }
}
