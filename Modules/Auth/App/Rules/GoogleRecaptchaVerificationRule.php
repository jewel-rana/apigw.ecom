<?php

namespace Modules\Auth\App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GoogleRecaptchaVerificationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            if(!app()->environment('local')) {
                if(!request()->filled('g-recaptcha-response')) {
                    $fail('Google reCAPTCHA is required.');
                    return;
                }
            }
        } catch (\Exception $exception) {
            $fail($exception->getMessage());
        }
    }
}
