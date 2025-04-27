<?php

namespace Modules\Provider\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderUserCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'provider_id' => 'required|integer|exists:providers,id',
            'name' => 'required',
            'email' => 'required|email|unique:provider_users,email',
            'mobile' => ['required', 'string', 'unique:provider_users,mobile'],
            'password' => 'required|string|min:8|max:32',
            'status' => 'required|in:active,inactive'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
