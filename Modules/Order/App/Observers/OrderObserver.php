<?php

namespace Modules\Order\App\Observers;

use Modules\Order\App\Models\Order;

class OrderObserver
{
    public function created(Order $order): void
    {
        if (! empty($order->status)) {
            $order->histories()->create([
                'old_value' => null,
                'new_value' => $order->status,
                'user_id' => auth('api')->id() ?? null,
                'remarks'     => 'Order created',
            ]);
        }
        if(!$order->customer->country_id || !$order->customer->city_id) {
            $order->customer->update($order->only(['country_id', 'city_id', 'code', 'address']));
        }
//                $order->customer->notify(new OrderInvoiceNotification($order));
    }

    public function updated(Order $order): void
    {
        if ($order->wasChanged('status')) {
            $order->histories()->create([
                'old_value' => $order->getOriginal('status'),
                'new_value' => $order->status,
                'user_id' => auth('api')->id() ?? null,
                'remarks'     => 'Order created',
            ]);
        }
    }

    public function deleted(Order $order): void
    {
        //
    }

    public function restored(Order $order): void
    {
        //
    }

    public function forceDeleted(Order $order): void
    {
        //
    }
}
