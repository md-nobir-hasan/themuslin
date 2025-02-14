<?php

namespace Modules\Campaign\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CampaignValidationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:191',
            'subtitle' => 'required|string',
            'image' => 'required|string',
            'status' => 'required|string',
            'start_date' => "nullable",
            'end_date' => "nullable",
            'id' => 'nullable',
            'slug' => 'required|unique:campaigns,slug,' . $this->id ?? 0,
            'products.product_id' => 'required|array',
            'products.campaign_price' => 'required|array',
            'products.units_for_sale' => 'required|array',
            'products.start_date' => 'nullable|array',
            'products.end_date' => 'nullable|array',
            'products.product_id.*' => 'required|exists:products,id',
            'products.campaign_price.*' => 'required|string',
            'products.units_for_sale.*' => 'required|string',
            'products.start_date.*' => 'nullable|date',
            'products.end_date.*' => 'nullable|date',
            'admin_id' => 'nullable',
            'vendor_id' => 'nullable',
            'type' => 'nullable',
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
                'title' => $this->campaign_name,
                'subtitle' => $this->campaign_subtitle,
                'image' => $this->image,
                'status' => $this->status,
                'start_date' => $this->campaign_start_date,
                'end_date' => $this->campaign_end_date,
                'slug' => $this->campaign_slug,
                "products" => [
                    'product_id' => $this->product_id,
                    'campaign_price' => $this->campaign_price,
                    'units_for_sale' => $this->units_for_sale,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'product_id.*' => $this->product_id,
                    'campaign_price.*' => $this->campaign_price,
                    'units_for_sale.*' => $this->units_for_sale,
                    'start_date.*' => $this->start_date,
                    'end_date.*' => $this->end_date,
                ]
            ] + $this->how_is_the_owner()
        );
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

    private function userId(){
        return Auth::guard("admin")->check() ? Auth::guard("admin")->user()->id : Auth::guard("vendor")->user()->id;
    }

    private function getGuardName(): string
    {
        return Auth::guard("admin")->check() ? "admin" : "vendor";
    }

    private function how_is_the_owner(): array
    {
        $arr = [];

        if($this->getGuardName() == "admin"){
            $arr = [
                "admin_id" => $this->userId(),
                "vendor_id" => null,
                "type" => $this->getGuardName(),
            ];
        }else{
            $arr = [
                "admin_id" => null,
                "vendor_id" => $this->userId(),
                "type" => $this->getGuardName(),
            ];
        }

        return $arr;
    }
}
