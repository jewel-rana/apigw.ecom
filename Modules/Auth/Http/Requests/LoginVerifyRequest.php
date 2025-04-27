<?php

namespace Modules\Auth\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\App\Rules\GoogleRecaptchaVerificationRule;
use Modules\Auth\App\Rules\OtpVerifyRule;

class LoginVerifyRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'reference' => ['required', 'string', new OtpVerifyRule()],
            'otp' => 'required',
            'g-recaptcha-response' => [ new GoogleRecaptchaVerificationRule()]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
