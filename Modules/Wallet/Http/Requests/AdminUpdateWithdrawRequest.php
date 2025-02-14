<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateWithdrawRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "note" => "nullable",
            "request_status" => "required",
            "id" => "required|integer",
            "request_image" => "nullable|mimes:jpeg,png,jpg,gif,svg,pdf,docx",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}