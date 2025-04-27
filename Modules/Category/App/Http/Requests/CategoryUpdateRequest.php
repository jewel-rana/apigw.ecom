<?php

namespace Modules\Category\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'service_type_id' => 'required|integer|exists:service_types,id',
            'parent_id' => 'nullable|integer',
            'name' => 'required|string',
            'code' => 'required|string|unique:categories,code,' . $this->category,
            'color' => 'required|string|in:' . implode(',', config('category.colors')),
            'attachment' => 'nullable|image|mimes:jpg,png,gif,svg|max:500'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
