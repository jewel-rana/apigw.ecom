<?php

namespace Modules\Region\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'code' => 'required|string|unique:languages,code,' . $this->language->id,
            'status' => 'required|in:1,0',
            'type' => 'required|string|in:rtl,ltr'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check() && !$this->language->is_default;
    }
}
