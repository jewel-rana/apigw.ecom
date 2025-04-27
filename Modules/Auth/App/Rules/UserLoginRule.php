<?php

namespace Modules\Auth\App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Constants\AuthConstant;
use Modules\Auth\Entities\User;
use Modules\Auth\Traits\AccessBlockingTrait;
use Modules\Auth\Traits\LoginTrait;
use Modules\Customer\App\Models\Customer;

class UserLoginRule implements ValidationRule
{
    use LoginTrait, AccessBlockingTrait;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            if($this->hasAlreadyBlocked($value)) {
                $fail('Sorry! Your account has blocked due to too many failed attempts.');
                return;
            }

            $user = User::where('email', $value)->first();

            if(is_null($user)) {
                $this->failedAttempt(request(), $value);
                $fail('email', __( 'No account associate with this email'));
            } else {
                if ($user->status != AuthConstant::USER_ACTIVE) {
                    $fail('email', __('Your account is not active'));
                }

                if (!Hash::check(request()->input('password'), $user->password)) {
                    $this->failedAttempt(request(), $value);
                    $fail('password', __('Password does not match'));
                }
            }
        } catch (\Exception $exception) {
            $this->failedAttempt(request(), $value);
            $fail(__('Internal server error'));
        }
    }
}
