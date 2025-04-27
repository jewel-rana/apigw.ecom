<?php

namespace Modules\Auth\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialiteAuthRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'provider' => 'required|string|in:google,facebook,twitter,apple,fib',
            'access_token' => 'required|string',
            'refresh_token' => 'nullable|string',
            'info' => 'nullable|array'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
