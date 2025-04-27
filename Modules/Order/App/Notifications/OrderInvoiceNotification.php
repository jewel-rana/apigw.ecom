<?php

namespace Modules\Order\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Order\App\Models\Order;

class OrderInvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Purchase Invoice')
            ->view('order::mail.invoice', [
                'customer' => $notifiable,
                'order' => $this->order,
                'payment' => $this->order->payment
            ]);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
