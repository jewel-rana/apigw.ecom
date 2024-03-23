<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class RecaptchaValidateRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            if(config('scopes.captcha_enabled')) {
                $response = Http::get("https://www.google.com/recaptcha/api/siteverify", [
                    'secret' => config('google.secret'),
                    'response' => $value
                ]);

                if (!($response->json()["success"] ?? false)) {
                    $fail(__('You are not a human.'));
                }
            }
        } catch (\Exception $exception) {
            $fail(__('Re-Captcha verification failed'));
        }
    }
}
