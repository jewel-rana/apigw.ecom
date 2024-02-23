<?php

namespace App\Http\Requests;

use App\Helpers\CommonHelper;
use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function authorize(): bool
    {
        return CommonHelper::hasPermission('role-update') && $this->role != 1;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:roles,name,' . $this->role,
            'permissions' => 'required|array'
        ];
    }
}
