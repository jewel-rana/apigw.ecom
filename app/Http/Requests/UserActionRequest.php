<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'action' => 'required|in:Active,Inactive',
            'remarks' => 'required|string'
        ];
    }
}
