<?php

namespace Modules\Auth\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Auth\Entities\Otp;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;
    private Otp $otp;

    public function __construct(Otp $otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(config('app.name') . ' - OTP')
            ->view('mail.otp', [
                'name' => $notifiable->name,
                'otp' => str_split($this->otp->code)
            ]);
    }
}
