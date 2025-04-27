<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'bail|required|string',
            'slug' => 'bail|required|unique:pages,slug,' . $this->page,
            'description' => 'bail|required',
            'status' => 'bail|nullable|in:0,1',
            'template' => 'nullable|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
