<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Slack\SlackMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use Carbon\Carbon;

class EarlyClockOutNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user, public Carbon $time) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database', 'mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🚨 Early Clock-Out')
            ->line("{$this->user->name} clocked out early at " . $this->time->format('H:i'))
            ->line('Please follow up if necessary.');
    }

    /**
     * Get the Slack representation of the notification.
     */
    // public function toSlack($notifiable): SlackMessage
    // {
    //     return (new SlackMessage)
    //         ->content("🚨 *{$this->user->name}* clocked out early at " . $this->time->format('H:i'));
    // }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'message' => "{$this->user->name} clocked out early at " . $this->time->format('H:i'),
            'user_id' => $this->user->id,
        ];
    }
}
