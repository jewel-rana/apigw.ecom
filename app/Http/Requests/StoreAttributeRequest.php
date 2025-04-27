<?php

namespace Modules\Category\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'lang' => 'required|string|exists:languages,code',
            'key' => 'required|string',
            'value' => 'required|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
