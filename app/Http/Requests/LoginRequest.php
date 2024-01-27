<?php

namespace App\Http\Requests;

use App\Rules\CustomerLoginRule;
use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'email' => ['bail', 'required', 'email', 'exists:customers,email', new CustomerLoginRule()],
            'password' => ['required', 'string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
