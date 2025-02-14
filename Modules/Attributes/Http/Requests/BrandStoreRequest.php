<?php

namespace Modules\Attributes\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class BrandStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(["name" => "string", "slug" => "string", "title" => "string", "description" => "string", "image_id" => "string", "banner_id" => "string"])]
    public function rules(): array
    {
        return [
            "name" => ["required","string" , Rule::unique('brands')->ignore($this->id ?? 0)],
            "slug" => ["required","string" , Rule::unique('brands')->ignore($this->id ?? 0)],
            "title" => ["required","string"],
            "description" => ["required","string"],
            "image_id" => ["required","string"],
            "banner_id" => ["required","string"],
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            "description" => str($this?->description)?->limit(190)?->value
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
