<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LeaveApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leave;

    public function __construct($leave)
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
        ->subject('Your Leave Has Been Approved')
        ->line("Your leave from {$this->leave->start_date} to {$this->leave->end_date} has been approved.")
        ->action('View Leave', url('/leaves/' . $this->leave->id));
}
}
