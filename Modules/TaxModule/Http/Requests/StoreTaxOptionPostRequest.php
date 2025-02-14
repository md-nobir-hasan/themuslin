<?php

namespace Modules\TaxModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxOptionPostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
//            'class_id' => "nullable",
            'tax_name' => "required",
            'country_id' => "nullable",
            'state_id' => "nullable",
            'city_id' => "nullable",
            'postal_code' => "nullable",
            'priority' => "nullable",
            'is_compound' => "nullable",
            'is_shipping' => "nullable",
            'rate' => "nullable",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
