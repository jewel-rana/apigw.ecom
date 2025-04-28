<?php

namespace App\Rules;

use App\Constants\AuthConstant;
use App\Models\Customer;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class CustomerLoginRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $customer = Customer::where('email', $value)->first();

            if(is_null($customer)) {
                $fail('email', __( 'No account associate with this email'));
                return;
            } else {

                if (strtolower($customer->status) != 'active') {
                    $fail(__('Your account is ' . $customer->status));
                    return;
                }

                if (!Hash::check(request()->input('password'), $customer->password)) {
                    $fail('password', __('Password does not match'));
                    return;
                }
            }
        } catch (\Exception $exception) {
            $fail(__('Internal server error'));
        }
    }
}
