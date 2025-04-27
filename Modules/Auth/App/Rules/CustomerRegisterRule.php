<?php

namespace Modules\Auth\App\Rules;

use App\Helpers\LogHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CustomerRegisterRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            if(!config('auth.register_enabled', false)) {
                $fail(__('Sorry! registration is currently disabled.'));
                return;
            }
        } catch (\Throwable $th) {
            LogHelper::error($th->getMessage(), [
                'keyword' => 'CustomerRegisterRule',
            ]);
            $fail('Internal Server Error');
            return;
        }
    }
}
