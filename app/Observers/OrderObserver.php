<?php

namespace App\Observers;

use App\Models\Order;
use App\Notifications\OrderCreateNotification;
use App\Notifications\OrderUpdateNotification;

class OrderObserver
{
    public function created(Order $order)
    {
        //$order->customer->notify(new OrderCreateNotification($order));
    }

    public function updated(Order $order)
    {
        $order->customer->notify(new OrderUpdateNotification($order));
    }

    public function deleted(Order $order)
    {

    }
}
