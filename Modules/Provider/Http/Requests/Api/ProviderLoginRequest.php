<?php

namespace Modules\Provider\Http\Requests\Api;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\App\Rules\GoogleRecaptchaVerificationRule;

class ProviderLoginRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:providers,email'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => [ new GoogleRecaptchaVerificationRule()]
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
