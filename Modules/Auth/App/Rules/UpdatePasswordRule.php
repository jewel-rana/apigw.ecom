<?php

namespace Modules\Auth\App\Rules;

use App\Helpers\LogHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            if (!Hash::check(request()->input('old_password'), auth()->user()->password)) {
                $fail('old_password', __('Sorry! old password does not match'));
            }
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PASSWORD_UPDATE_RULE_EXCEPTION'
            ]);
            $fail('password', __('Failed to update password'));
        }
    }
}
