<?php

namespace Modules\Auth\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Schema;
use Modules\Auth\Entities\Otp;
use Modules\Auth\Traits\AccessBlockingTrait;
use Modules\Auth\Traits\Auth2FaTrait;

class Validate2FaRule implements ValidationRule
{
    use Auth2FaTrait, AccessBlockingTrait;

    private string $start;

    public function __construct()
    {
        $this->start = now()->subMinutes(5)->format('Y-m-d H:i:s');
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $request = request();
        $identity = $this->decryptToken($request->input('token'));

        try {
            if(!$identity) {
                $fail('Invalid token.');
                return;
            }

            if (!Schema::hasTable('otps')) {
                $fail('Sorry! application not configured properly.');
                return;
            }

            if($this->hasAlreadyBlocked($identity)) {
                $fail('Sorry! Your account has blocked due to too many failed attempts.');
                return;
            }

            $otp = Otp::where('reference', $request->input('token'))->first();

            if (!$otp) {
                $this->failedAttempt($request, $identity, 'OTP_ATTEMPT');
                $fail('otp', 'Your OTP is invalid');
                return;
            }

            if ($otp->code != request()->input('code')) {
                $this->failedAttempt($request, $identity, 'OTP_ATTEMPT');
                $fail('otp', 'Your otp does not match.');
                return;
            }

            if ($otp->updated_at < now()->subMinutes(5)) {
                $fail('otp', 'Your otp already expired.');
                return;
            }

            if ($otp->updated_at < now()->subMinutes(5)) {
                $fail('otp', 'Your otp already expired.');
                return;
            }

            if ($otp->revoked) {
                $this->failedAttempt($request, $identity, 'OTP_ATTEMPT');
                $fail('otp', 'Your otp already used.');
                return;
            }
        } catch (\Exception $exception) {
            $this->failedAttempt($request, $identity, 'OTP_ATTEMPT');
            $fail('Internal server error!');
            return;
        }
    }
}
