<?php

namespace App\Http\Requests;

use App\Helpers\CommonHelper;
use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserActionRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function authorize(): bool
    {
        return CommonHelper::hasPermission('user-update');
    }

    public function rules(): array
    {
        return [
            'action' => 'required|in:Active,Inactive',
            'remarks' => 'required|string|min:5|max:250'
        ];
    }
}
