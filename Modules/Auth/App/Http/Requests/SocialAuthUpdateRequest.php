<?php

namespace Modules\Auth\App\Http\Requests;

use App\Traits\FormValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\App\Rules\SocialCustomerEmailUnique;

class SocialAuthUpdateRequest extends FormRequest
{
    use FormValidationResponseTrait;

    public function rules(): array
    {
        return [
            'profile_id' => 'required|string|uuid|exists:socialites,uuid',
            'name' => 'required|string',
            'email' => ['required', 'email', new SocialCustomerEmailUnique(request()->input('profile_id'))],
            'mobile' => 'nullable|string',
            'city_id' => 'nullable|integer',
            'country_id' => 'nullable|integer',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:6|max:18|same:password_confirm'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
