<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Leave;

class LeaveRestoredNotification extends Notification
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
            ->subject('Leave Balance Restored')
            ->line("A leave request by {$this->leave->user->name} was rejected or cancelled.")
            ->line("Restored days: {$this->leave->days_requested}")
            ->line("Leave Period: {$this->leave->start_date} to {$this->leave->end_date}")
            ->line('Leave balance has been updated accordingly.');
    }
}
