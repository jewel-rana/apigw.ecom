<?php

namespace Modules\Payment\App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Order\App\Models\Order;

class PaymentUpdateStatus implements ShouldBroadcast
{
    use SerializesModels, Dispatchable;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('order.' . $this->order->id);
    }

    public function broadcastAs(): string
    {
        return 'payment.update';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->order->id,
            'amount' => $this->order->total_payable,
            'status' => $this->order->status,
            'payment' => $this->order->payment?->format()
        ];
    }
}
