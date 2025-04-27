<?php

namespace Modules\Auth\App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Constants\AuthConstant;
use Modules\Customer\App\Models\Customer;

class CustomerLoginRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            if(!config('auth.default_login_enabled', false)) {
                $fail('The customer login is disabled.');
                return;
            }

            $customer = Customer::where('email', $value)->first();

            if (is_null($customer)) {
                $fail('email', __('No account associate with this email'));
                return;
            }

            if (!in_array($customer->status, [AuthConstant::CUSTOMER_ACTIVE])) {
                $fail('email', __('Your account is ' . ucfirst($customer->status)));
                return;
            }

            if (!Hash::check(request()->input('password'), $customer->password)) {
                $fail('password', __('Your password does not match'));
                return;
            }
        } catch (\Exception $exception) {
            $fail(__('Internal server error'));
            return;
        }
    }
}
