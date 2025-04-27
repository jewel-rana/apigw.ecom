<?php

namespace Modules\Region\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegionUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'code' => 'required|string|unique:regions,code,' . $this->region
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
