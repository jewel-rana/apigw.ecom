<?php

namespace Modules\Provider\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gateway_ids' => 'required|array',
            'name' => 'required|string',
            'email' => 'required|string|unique:providers,email',
            'password' => 'required|string|min:8|max:32',
            'balance' => 'required|integer',
            'status' => 'required|in:0,1'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
