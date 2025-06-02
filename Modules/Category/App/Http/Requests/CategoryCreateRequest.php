<?php

namespace Modules\Category\App\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class CategoryCreateRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|integer',
            'name' => 'required|string',
            'slug' => 'nullable|string|unique:categories,slug',
            'color' => 'nullable|string|in:' . implode(',', config('category.colors')),
            'attachment' => 'nullable|image|mimes:jpg,png,gif,svg|max:500'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
