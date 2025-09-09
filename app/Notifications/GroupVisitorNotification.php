<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Services\SmsService;

class GroupVisitorNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $visitors;

    public function __construct(array $visitors)
    {
        $this->visitors = $visitors;
    }

    /**
     * Determine the notification delivery channels.
     */
    public function via($notifiable): array
    {
        $channels = ['mail']; // Default to email
        if ($notifiable->phone) {
            $channels[] = 'sms'; // We'll define a custom SMS channel
        }
        return $channels;
    }

    /**
     * Email representation
     */
    public function toMail($notifiable): MailMessage
    {
        $visitorNames = implode(', ', array_map(fn($v) => $v->full_name, $this->visitors));

        return (new MailMessage)
            ->subject('New Visitors Registered')
            ->greeting("Hello {$notifiable->name},")
            ->line("The following visitors have been registered:")
            ->line($visitorNames)
            ->line('Please take necessary actions if needed.');
    }

    /**
     * SMS representation
     */
    public function toSms($notifiable)
    {
        $visitorNames = implode(', ', array_map(fn($v) => $v->full_name, $this->visitors));
        return "New visitors registered: {$visitorNames}";
    }

    /**
     * Send SMS using your SmsService
     */
    public function sendSms($notifiable)
    {
        if ($notifiable->phone) {
            $smsService = app(SmsService::class);
            $smsService->send($notifiable->phone, $this->toSms($notifiable));
        }
    }

    /**
     * Override the "send" method for custom SMS
     */
    public function notify($notifiable)
    {
        // First send email if available
        if ($notifiable->email) {
            $notifiable->notify($this); // via Mail
        }

        // Then send SMS
        $this->sendSms($notifiable);
    }
}
