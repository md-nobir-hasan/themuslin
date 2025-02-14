<?php

namespace Modules\ShippingModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShippingMethodRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "title" => "required",
            "zone_id" => "required",
            "status_id" => "required",
            "cost" => "required",
            "vendor_id" => "nullable",
        ];
    }

    protected function prepareForValidation()
    {
        $arr = [];

        if(auth("vendor")->check()){
            $arr = ["vendor_id" => auth()->guard("vendor")->id()];
        }

        if(auth("sanctum")->check()){
            $arr = ["vendor_id" => auth()->guard("sanctum")->id()];
        }

        return $this->merge([
            "status_id" => $this->status,
        ] + $arr);
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
