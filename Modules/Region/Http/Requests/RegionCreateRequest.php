<?php

namespace Modules\Region\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegionCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'code' => 'required|string|unique:regions,code'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
