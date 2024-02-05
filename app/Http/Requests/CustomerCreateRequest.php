<?php

namespace App\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class CustomerCreateRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'mobile' => 'nullable|string|unique:customers,mobile',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:6|max:18|same:password_confirm',
            'gender' => 'nullable|string|in:male,female',
            'address' => 'nullable|string'
        ];
    }
}
