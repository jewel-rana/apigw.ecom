<?php

namespace App\Http\Requests;

use App\Rules\RecaptchaValidateRule;
use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:customers,email',
            'mobile' => 'required|string|unique:customers,mobile',
            'password' => 'required|string|min:8|max:32|same:password_confirm',
            'gender' => 'nullable|in:male,female',
            'recaptcha_token' => ['required', 'string', new RecaptchaValidateRule()]
        ];
    }
}
