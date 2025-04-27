<?php

namespace Modules\Provider\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderUserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'provider_id' => 'required|integer|exists:providers,id',
            'name' => 'required',
            'email' => 'required|email|unique:provider_users,email,' . $this->user,
            'mobile' => ['required', 'string', 'unique:provider_users,mobile,' . $this->user],
            'status' => 'required|in:active,inactive'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
