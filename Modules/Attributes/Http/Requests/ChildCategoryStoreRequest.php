<?php

namespace Modules\Attributes\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChildCategoryStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:191|unique:child_categories',
            'slug' => 'required|string|max:191|unique:child_categories',
            'description' => 'nullable',
            'status_id' => 'nullable|max:191',
            'image_id' => 'nullable|max:191',
            'banner_id' => 'nullable|max:191',
            'm_banner_id' => 'nullable|max:191',
            'category_id' => 'required|max:191',
            'sub_category_id' => 'required|max:191',
            'sort_order' => 'nullable',
            'show_home' => 'nullable',
            'featured' => 'nullable',
        ];
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
