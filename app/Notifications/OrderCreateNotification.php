<?php

namespace App\Notifications;

use App\Helpers\CommonHelper;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Invoice')
            ->view('mail.order.order', [
                'order' => $this->order,
                'promotion_logo' => CommonHelper::getPromotionLogo($this->order->promotion)
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
