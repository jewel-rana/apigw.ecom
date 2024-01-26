<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'mobile' => 'required|string|unique:users,mobile,' . $this->user,
            'email' => 'required|email|unique:users,email,' . $this->user,
            'password' => 'nullable|string|min:6|max:18|same:password_confirm',
            'gender' => 'required|string|in:male,female'
        ];
    }
}
