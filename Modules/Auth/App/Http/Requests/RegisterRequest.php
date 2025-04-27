<?php

namespace Modules\Auth\App\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\App\Rules\CustomerRegisterRule;
use Modules\Auth\App\Rules\GoogleRecaptchaVerificationRule;

class RegisterRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'address' => 'nullable|string',
            'gender' => 'required|string:in:male,female,other,Male,Female,Other',
            'email' => ['required', 'email', 'unique:customers,email', new CustomerRegisterRule()],
            'mobile' => 'required|unique:customers,mobile',
            'password' => 'required|string|same:password_confirm|min:8|max:32',
            'g-recaptcha-response' => [ new GoogleRecaptchaVerificationRule()]
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
