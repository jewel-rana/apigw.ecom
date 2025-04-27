<?php

namespace Modules\Auth\App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Auth\Entities\Otp;

class OtpVerifyRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $otp = Otp::where('created_at', '>=', now()->subMinutes(5))
                ->where('reference', $value)
                ->where('revoked', false)
                ->first();
            if(!$otp) {
                $fail('otp', __('Your otp not found!'));
                return;
            } else {
                if ($otp->updated_at < now()->subMinutes(5)) {
                    $fail('otp', __('Your otp expired!'));
                    return;
                }
                if ($otp->code != request()->input('otp')) {
                    $fail('otp', __('Your otp does not match!'));
                    return;
                }
                if ($otp->revoked) {
                    $fail('otp', __('Sorry! your otp already used.'));
                    return;
                }
            }
        } catch (\Exception $exception) {
            $fail($attribute, 'Internal Server Error');
            return;
        }
    }
}
