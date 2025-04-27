<?php

namespace Modules\Auth\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\App\Rules\OtpVerifyRule;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'reference' => ['required', 'string', new OtpVerifyRule()],
            'otp' => 'required|min:6|max:6',
            'password' => 'required|string|same:password_confirm|min:8|max:32'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
