<?php

namespace Modules\Order\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Models\OrderItem;

class OrderDeliveryNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public Order $order;
    public OrderItem $orderItem;
    public array $vouchers;
    public function __construct(Order $order, OrderItem $orderItem, array $vouchers)
    {
        $this->order = $order;
        $this->orderItem = $orderItem;
        $this->vouchers = $vouchers;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Order delivery')
            ->view('order::mail.deliver', [
                'customer' => $notifiable,
                'order' => $this->order,
                'item' => $this->orderItem,
                'denomination' => $this->orderItem->bundle,
                'vouchers' => $this->vouchers
            ]);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
