<?php

namespace Modules\Order\App\Observers;

use Modules\Order\App\Models\Order;

class OrderObserver
{
    public function created(Order $order): void
    {
        if(!$order->customer->country_id || !$order->customer->city_id) {
            $order->customer->update($order->only(['country_id', 'city_id', 'code', 'address']));
        }
//                $order->customer->notify(new OrderInvoiceNotification($order));
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
