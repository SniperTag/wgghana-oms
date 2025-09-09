<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Jobs\SendSmsJob;

class VisitorRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $visitor;

    public function __construct($visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * Dynamically select channels.
     */
    public function via($notifiable)
    {
        if (!empty($notifiable->email)) {
            return ['mail'];
        } elseif (!empty($notifiable->phone)) {
            return ['sms']; // We'll map this to the job
        }

        return ['database'];
    }

    /**
     * Email notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Visitor Registered')
            ->greeting("Hello {$notifiable->name},")
            ->line("A new visitor has registered.")
            ->line("Visitor: {$this->visitor->name}")
            ->line("Purpose: {$this->visitor->purpose}")
            ->action('View Visitor', url('/visitors/' . $this->visitor->id))
            ->line('Thank you.');
    }

    /**
     * SMS notification (dispatch the job).
     */
    public function toSms($notifiable)
    {
        $message = "Hello {$notifiable->name}, "
            . "you have a new visitor: {$this->visitor->name}, "
            . "Purpose: {$this->visitor->purpose}.";

        SendSmsJob::dispatch($notifiable->phone, $message);
    }

    /**
     * Database fallback.
     */
    public function toArray($notifiable)
    {
        return [
            'visitor_id'   => $this->visitor->id,
            'visitor_name' => $this->visitor->name,
            'purpose'      => $this->visitor->purpose,
        ];
    }
}
