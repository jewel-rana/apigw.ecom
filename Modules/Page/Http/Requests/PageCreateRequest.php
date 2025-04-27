<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'bail|required|string',
            'slug' => 'bail|required|unique:pages,slug',
            'description' => 'bail|required',
            'attribute' => 'bail|nullable|array',
            'template' => 'bail|nullable|string'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
