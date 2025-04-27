<?php

namespace Modules\Region\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'code' => 'required|string|unique:languages,code',
            'status' => 'required|in:1,0',
            'type' => 'required|string|in:rtl,ltr'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
