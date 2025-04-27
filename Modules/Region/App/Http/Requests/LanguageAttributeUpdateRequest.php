<?php

namespace Modules\Region\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageAttributeUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'language' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
