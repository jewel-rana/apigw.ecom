<?php

namespace Modules\Auth\App\Rules;

use App\Helpers\LogHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Customer\App\Models\Customer;
use Modules\Auth\App\Models\Socialite as SocialLogin;

class SocialCustomerEmailUnique implements ValidationRule
{
    private ?string $profileId;
    public function __construct($profileId = null)
    {
        $this->profileId = $profileId;
    }
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $social = SocialLogin::where('uuid', $this->profileId)->first();
            $customer = Customer::where('email', $value);
            if($social->customer) {
                $customer->whereNotIn('id', [$social->customer->id]);
            }
            if($customer->count()) {
                $fail(__('The email has already taken'));
            }
        } catch (\Exception $exception) {
            LogHelper::exception($exception, ['keyword' => 'SOCIAL_EMAIL_UNIQUE_RULE', 'request-payload' => request()->all()]);
            $fail(__('Internal error!'));
        }
    }
}
