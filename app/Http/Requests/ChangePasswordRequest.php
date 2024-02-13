<?php

namespace App\Http\Requests;

use App\Rules\PasswordRule;
use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    use FormValidationResponseTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'old_password' => ['required', 'string', 'min:6', 'max:18'],
            'password' => ['required', 'string', 'min:6', 'max:18', 'same:password_confirm', new PasswordRule()]
        ];
    }
}
