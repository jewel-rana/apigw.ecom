<?php

namespace Modules\Report\App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReportExportedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private User $user;
    private string $url;

    public function __construct(User $user, $url)
    {
        $this->user = $user;
        $this->url = $url;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Hello, ' . $this->user->name)
            ->line('Your card report exported successfully.')
            ->action('Download report here', $this->url)
            ->line('This link will expire in 30 minutes')
            ->line('Thank you');
    }

}
