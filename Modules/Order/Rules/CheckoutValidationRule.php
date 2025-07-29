<?php

namespace Modules\Order\Rules;

use App\Helpers\LogHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Cart\App\Services\CartService;

class CheckoutValidationRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $cart = (new CartService())->getCarts(request());
            if(!$cart) {
                $fail('cart', "Cart is empty");
                return;
            }

            if(!count($cart['items'])) {
                $fail("Your cart has no items.");
                return;
            }
        } catch (\Exception $exception) {
            LogHelper::error($exception, [
                'keyword' => 'CheckoutValidationRule',
            ]);
            $fail($exception->getMessage());
        }
    }
}
