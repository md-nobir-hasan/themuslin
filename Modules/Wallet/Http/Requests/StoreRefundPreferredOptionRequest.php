<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRefundPreferredOptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "id" => "sometimes",
            'name' => ['required'],
            'fields' => ['nullable'],
            "status_id" => "required|integer",
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            "fields" => serialize($this->filed),
            "name" => $this->gateway_name
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}
