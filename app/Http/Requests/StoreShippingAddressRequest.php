<?php

namespace App\Http\Requests;

use Auth;
use HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreShippingAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            "shipping_address_name" => "required",
            "name" => "required|string",
            "address" => "required|string",
            "country_id" => "required|integer",
            "state_id" => "required|integer",
            "city" => "required|string",
            "zip_code" => "nullable|integer",
            "phone" => "required|string",
            "email" => "required|string",
            "user_id" => "required"
        ];
    }

    /**
     * @throws HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors(); // Here is your array of errors
        return throw new HttpResponseException($errors);
    }

    protected function prepareForValidation()
    {
        if(Auth::guard("web")->check()){
            $user_id = Auth::guard("web")->user()->id;
        }elseif(Auth::guard("sanctum")->check()){
            $user_id = Auth::guard("sanctum")->user()->id;
        }else{
            $user_id = null;
        }

        return $this->merge([
            "user_id" => $user_id
        ]);
    }

    public function messages(): array
    {
        return [
            "user_id.required" => "Please login first before creating Shipping Address"
        ];
    }
}
