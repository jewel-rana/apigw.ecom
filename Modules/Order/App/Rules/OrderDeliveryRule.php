<?php

namespace Modules\Order\App\Rules;

use App\Helpers\LogHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Order\App\Constant\OrderConstant;
use Modules\Order\App\Constant\OrderDeliveryConstant;
use Modules\Order\App\Models\Order;

class OrderDeliveryRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $order = Order::find($value);
            if(!$order){
                $fail('order_id', __('Order not found'));
                return;
            }

            if($order->isNotOwner()) {
                $fail('order_id', __('Restricted property!'));
                return;
            }

            if($order->items->first()->operator?->is_in_app_deliverable) {
                $fail('order_id', __('Delivery not applicable'));
                return;
            }

            if($order->deliveries()->where('status', OrderDeliveryConstant::SUCCESS)->count() >= OrderConstant::MAX_DELIVERY_ATTEMPTS) {
                $fail('order_id', __('Delivery limit reached'));
                return;
            }
        } catch (\Exception $exception) {
            LogHelper::error($exception, [
                'keyword' => 'OrderDeliveryRule',
                'order-id' => $value
            ]);
            $fail(__('Internal Server Error'));
        }
    }
}
