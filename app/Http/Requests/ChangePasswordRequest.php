<?php

namespace App\Http\Requests;

use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
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
