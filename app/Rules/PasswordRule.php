<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class PasswordRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(request()->user()) {
            $user = request()->user();
            if(request()->filled('old_password')) {
                if(request()->input('old_password') == request()->input('password')) {
                    $fail('Password could not be same with old password');
                }
                if (!Hash::check(request()->input('old_password'), $user->password)) {
                    $fail('Old password does not match');
                }
            }
        }
    }
}
