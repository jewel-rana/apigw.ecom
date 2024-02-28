<?php

namespace App\Rules;

use App\Constants\AuthConstant;
use App\Helpers\LogHelper;
use App\Models\Otp;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OtpValidateRule implements ValidationRule
{
    private string $type;

    public function __construct($type = 'verify')
    {
        $this->type = $type;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $otp = Otp::where('reference', request()->input('reference'))->first();

            if(!$otp) {
                $fail(__('Invalid token'));
            }

            if($otp->created_at->lte(now()->subMinutes(5))) {
                $fail(__('Token expired!'));
            }

            if($otp->code != request()->input('otp')) {
                $fail('otp', __('Your OTP does not match'));
            }

            if($this->type == 'passed' && $otp->status != AuthConstant::OTP_VERIFIED) {
                $fail('otp', __('You have not passed the OTP validation'));
            }
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'OTP_VALIDATE_EXCEPTION'
            ]);
            $fail(__('Internal server error!'));
        }
    }
}
