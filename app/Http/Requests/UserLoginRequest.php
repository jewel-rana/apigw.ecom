<?php

namespace App\Http\Requests;

use App\Rules\UserLoginRule;
use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email', new UserLoginRule()],
            'password' => ['required', 'string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
