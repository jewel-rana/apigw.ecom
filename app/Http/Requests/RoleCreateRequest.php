<?php

namespace App\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class RoleCreateRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array'
        ];
    }
}
