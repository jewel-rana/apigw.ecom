<?php

namespace Modules\Auth\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\App\Rules\CustomerLoginRule;
use Modules\Auth\App\Rules\GoogleRecaptchaVerificationRule;

class CustomerLoginRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:customers,email', new CustomerLoginRule()],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => [ new GoogleRecaptchaVerificationRule()]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
