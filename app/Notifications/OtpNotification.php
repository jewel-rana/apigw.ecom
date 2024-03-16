<?php

namespace App\Notifications;

use App\Models\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Otp $otp;

    public function __construct(Otp $otp)
    {
        $this->otp = $otp;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Forgot password OTP')
            ->view('mail.order.otp', [
                'name' => $notifiable->name,
                'otp' => str_split($this->otp->code)
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
