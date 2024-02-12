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
            } else {

                if ($customer->status != AuthConstant::STATUS_ACTIVE) {
                    $fail(__('Your account is ' . $customer->status));
                }

                if (!Hash::check(request()->input('password'), $customer->password)) {
                    $fail('password', __('Password does not match'));
                }
            }
        } catch (\Exception $exception) {
            $fail(__('Internal server error'));
        }
    }
}
