<?php

namespace Modules\Auth\App\Rules;

use App\Helpers\LogHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Customer\App\Models\Customer;

class ForgotPasswordRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            if(!config('auth.forgot_password_enabled', false)) {
                $fail('The customer login is disabled.');
                return;
            }

            $customer = Customer::where('email', $value)->first();
            if(!in_array($customer->status, ['active', 'pending'])) {
                $fail('email', __('Sorry! your account is :status', ['status' => $customer->status]));
            }
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'CUSTOMER_FORGOT_PASSWORD_RULE'
            ]);
            $fail('email', __('Internal server error!'));
        }
    }
}
