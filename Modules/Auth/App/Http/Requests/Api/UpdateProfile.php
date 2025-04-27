<?php

namespace Modules\Auth\App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:customers,email,' . auth('api')->id(),
            'mobile' => 'nullable|string',
            'gender' => 'nullable|string|in:male,female,other',
            'country_id' => 'required|integer|exists:countries,id',
            'city_id' => 'required|integer|exists:cities,id',
            'code' => 'nullable|numeric',
            'address' => 'nullable|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
