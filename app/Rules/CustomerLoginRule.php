<?php

namespace App\Rules;

use App\Constants\AppConstant;
use App\Constants\AuthConstant;
use App\Models\Customer;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class CustomerLoginRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $customer = Customer::where('email', $attribute)->first();

        if($customer->status != AuthConstant::STATUS_ACTIVE) {
            $fail(__('Your account is ' . $customer->status));
        }

        if(is_null($customer->email_verified_at)) {
            $fail(__('Your account is not verified'));
        }

        if (!Hash::check(request()->input('password'), $customer->password)) {
            $fail(__('Password does not match'));
        }
    }
}
