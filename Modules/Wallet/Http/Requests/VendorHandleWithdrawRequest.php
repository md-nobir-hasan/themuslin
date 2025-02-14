<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorHandleWithdrawRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "gateway_id" => "required",
            "gateway_fields" => "required",
            "amount" => "required",
            "vendor_id" => "required",
            "request_status" => "required"
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            "gateway_id" => $this->gateway_name,
            "gateway_fields" => json_encode($this->gateway_filed),
            "amount" => $this->withdraw_amount,
            "vendor_id" => $this->type == 'api' ? auth('sanctum')->id() : auth("vendor")->id() ?? null,
            "request_status" => "pending"
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}