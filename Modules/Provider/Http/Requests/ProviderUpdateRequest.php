<?php

namespace Modules\Provider\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gateway_ids' => 'required|array',
            'name' => 'required|string',
            'email' => 'required|string|unique:providers,email,' . $this->provider,
            'password' => 'nullable|string|min:8|max:32',
            'balance' => 'required|integer',
            'status' => 'required|in:0,1'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
