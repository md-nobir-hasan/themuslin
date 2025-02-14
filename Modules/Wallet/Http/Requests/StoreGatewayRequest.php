<?php

namespace Modules\Wallet\Http\Requests;

use App\Helpers\SanitizeInput;
use Illuminate\Foundation\Http\FormRequest;

class StoreGatewayRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "id" => "sometimes",
            "name" => "required|string",
            "filed" => "required|string",
            "status_id" => "required|integer",
        ];
    }

    protected function prepareForValidation()
    {
        $fields = [];
        foreach($this->filed ?? [] as $key => $value){
            $fields[$key] = SanitizeInput::esc_html($value);
        }

        return $this->merge([
            "filed" => serialize($fields),
            "name" => SanitizeInput::esc_html($this->gateway_name)
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}