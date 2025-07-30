<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\Appointment;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentScheduledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting("Hello {$notifiable->full_name},")
            ->line('Your appointment has been successfully scheduled.')
            ->line('Scheduled At: ' . \Carbon\Carbon::parse($this->appointment->scheduled_at)->format('l, jS F Y \a\t g:i A'))
            ->action('View Appointment', route('appointments.checkin', $this->appointment->id))
            ->line('Please arrive on time. Thank you!');
    }

    public function toArray($notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'scheduled_at' => $this->appointment->scheduled_at,
        ];
    }
}
