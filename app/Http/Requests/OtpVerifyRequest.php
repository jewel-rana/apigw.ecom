<?php

namespace App\Http\Requests;

use App\Rules\OtpValidateRule;
use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class OtpVerifyRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'reference' => ['required', 'string', new OtpValidateRule('verify')],
            'otp' => 'required|string|min:6|max:6'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
