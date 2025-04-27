<?php

namespace Modules\Auth\App\Http\Requests\Api;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\App\Rules\UpdatePasswordRule;

class UpdatePasswordRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', new UpdatePasswordRule()],
            'password_confirm' => ['required', 'string', 'same:password']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
