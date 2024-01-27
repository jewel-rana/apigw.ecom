<?php

namespace App\Rules;

use App\Constants\AuthConstant;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class UserLoginRule implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::where('email', $attribute)->first();

        if ($user->status != AuthConstant::STATUS_ACTIVE) {
            $fail(__('Your account is ' . $user->status));
        }

        if (is_null($user->email_verified_at)) {
            $fail(__('Your account is not verified'));
        }

        if (!Hash::check(request()->input('password'), $user->password)) {
            $fail(__('Password does not match'));
        }
    }
}
