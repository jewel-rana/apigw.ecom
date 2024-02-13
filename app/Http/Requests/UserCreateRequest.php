<?php

namespace App\Http\Requests;

use App\Helpers\CommonHelper;
use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function authorize(): bool
    {
        return CommonHelper::hasPermission(['user-create']);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'mobile' => 'required|string|unique:users,mobile',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|max:18|same:password_confirm',
            'gender' => 'nullable|string|in:male,female',
            'role_id' => 'required|integer|exists:roles,id'
        ];
    }
}
