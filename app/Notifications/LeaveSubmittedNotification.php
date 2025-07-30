<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Leave;

class LeaveSubmittedNotification extends Notification
{
    public $leave;

    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Leave Request Submitted')
            ->greeting("Hello {$notifiable->name},")
            ->line("{$this->leave->user->name} submitted a leave request.")
            ->line("From: {$this->leave->start_date} To: {$this->leave->end_date}")
            ->action('Review Request', url("/admin/leaves/{$this->leave->id}"));
    }
}
