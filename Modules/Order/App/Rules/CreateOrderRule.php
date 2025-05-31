<?php

namespace Modules\Order\App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Product\Constants\ProductConstant;
use Modules\Product\Entities\Product;

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

                    $product = Product::find($item['product_id']);
                    if(!$product) {
                        $fail(__('Product not found'));
                        return;
                    }

                    if($product->status !== ProductConstant::STATUS_ACTIVE) {
                        $fail(__('The product is not active.'));
                        return;
                    }

                    if(!$product->supplier->status) {
                        $fail(__('The supplier of the product is not active.'));
                        return;
                    }
                }
            }
        } catch (\Throwable $th) {
            $fail($th->getMessage());
            return;
        }
    }
}
