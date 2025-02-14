<?php

namespace Modules\MobileApp\Http\Requests\MobileSlider;

use Illuminate\Foundation\Http\FormRequest;

class StoreMobileSliderRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            "title" => "required",
            "description" => "required",
            "image_id" => "required",
            "button_text" => "required",
            "url" => "required",
            "category" => "nullable",
            "campaign" => "nullable",
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            "image_id" => $this->image,
            "url" => $this->button_url,
            "category" => $this->category_type ? $this->category ?? null : null,
            "campaign" => $this->category_type ? null : $this->campaign ?? null,
        ]);
    }
}
