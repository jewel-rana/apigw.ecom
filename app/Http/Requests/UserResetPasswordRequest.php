<?php

namespace App\Http\Requests;

use App\Rules\OtpValidateRule;
use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserResetPasswordRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => ['required', 'uuid', 'exists:otps,reference', new OtpValidateRule('passed')],
            'password' => 'required|string|min:6|max:18|same:password_confirm'
        ];
    }
}
