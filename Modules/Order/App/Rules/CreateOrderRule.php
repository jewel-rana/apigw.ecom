<?php

namespace Modules\Order\App\Rules;

use App\Helpers\CommonHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Bundle\App\Models\BundlePurchaseLimit;
use Modules\Bundle\Entities\Bundle;
use Modules\Bundle\Services\BundlePurchaseLimitService;

class CreateOrderRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            foreach ($value as $item) {
                if(array_key_exists('product_id', (array) $item)) {

                    $bundle = Bundle::find($item['product_id']);
                    if(!$bundle) {
                        $fail(__('Product not found'));
                        return;
                    }

                    if(!$bundle->status) {
                        $fail(__('Product is not inactive.'));
                        return;
                    }

                    if(!$bundle->operator->status) {
                        $fail(__('The brand of the product is not active.'));
                        return;
                    }

                    $rules = BundlePurchaseLimit::where('bundle_id', $item['product_id'])->get();
                    foreach ($rules as $rule) {
                        if (!(new BundlePurchaseLimitService())->{$rule->limit_type}($rule, $item['product_id'])) {
                            $fail(__('You have exceeded the :limit purchase limit.', ['limit' => CommonHelper::parseLimitType($rule->limit_type)]));
                            return;
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            $fail($th->getMessage());
            return;
        }
    }
}
