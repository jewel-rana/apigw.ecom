<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'mobile' => 'required|string|unique:users,mobile',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|max:18|same:password_confirm',
            'gender' => 'required|string|in:male,female',
            'role_id' => 'required|integer|exists:roles,id'
        ];
    }
}
