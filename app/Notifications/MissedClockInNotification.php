<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\NexmoMessage; // or use SmsMessage for Twilio or your SMS driver
use Illuminate\Notifications\Notification;

class MissedClockInNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $date;

    /**
     * Create a new notification instance.
     *
     * @param string $date Date for which the staff missed clock-in
     */
    public function __construct(string $date)
    {
        $this->date = $date;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'slack', 'database', 'nexmo'];
        // Replace 'nexmo' with your SMS channel if different
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Missed Clock-In Alert')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("You missed clocking in on {$this->date}. Please ensure to clock in on time to avoid any issues with attendance tracking.")
            ->action('Login to Portal', url('/login'))
            ->line('If you believe this is a mistake, please contact your supervisor or HR.');
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack(object $notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->success()
            ->content("⚠️ Missed Clock-In Alert: {$notifiable->name} missed clocking in on {$this->date}.");
    }

    /**
     * Get the SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\NexmoMessage
     */
    // public function toNexmo(object $notifiable): NexmoMessage
    // {
    //     return (new NexmoMessage)
    //         ->content("Alert: You missed clocking in on {$this->date}. Please clock in promptly next time.");
    // }

    /**
     * Get the array representation of the notification (for database channel).
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Missed clock-in on {$this->date}",
            'user_id' => $notifiable->id,
            'date' => $this->date,
        ];
    }
}
