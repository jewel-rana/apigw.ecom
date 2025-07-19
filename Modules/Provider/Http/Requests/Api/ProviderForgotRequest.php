<?php

namespace Modules\Provider\Http\Requests\Api;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Provider\Rules\Api\ProviderForgotPasswordRule;

class ProviderForgotRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:providers,email', new ProviderForgotPasswordRule()]
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
