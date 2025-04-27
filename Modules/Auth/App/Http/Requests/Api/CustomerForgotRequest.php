<?php

namespace Modules\Auth\App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\App\Rules\ForgotPasswordRule;

class CustomerForgotRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:customers,email', new ForgotPasswordRule()]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
