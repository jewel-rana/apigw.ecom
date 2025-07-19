<?php

namespace Modules\Provider\Rules\Api;

use App\Helpers\LogHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Provider\Entities\Provider;

class ProviderForgotPasswordRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            if(!config('auth.forgot_password_enabled', false)) {
                $fail('The supplier forgot password is disabled.');
                return;
            }

            $provider = Provider::where('email', $value)->first();
            if(!in_array($provider->status, ['active', 'pending'])) {
                $fail('email', __('Sorry! your account is :status', ['status' => $provider->status]));
                return;
            }
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'CUSTOMER_FORGOT_PASSWORD_RULE'
            ]);
            $fail('email', __('Internal server error!'));
        }
    }
}
