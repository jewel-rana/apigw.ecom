<?php

namespace App\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserActionRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'action' => 'required|in:Active,Inactive',
            'remarks' => 'required|string|min:5|max:250'
        ];
    }
}
