<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;
class AppointmentApprovalRequestNotification extends Notification
{
    

    /**
     * Create a new notification instance.
     */
    protected  $appointment;
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/appointments/' . $this->appointment->id . '/review');
        return (new MailMessage)
            ->greeting('Hello ' . $notifiable->full_name)
            ->line("An appointment scheduled for {$this->appointment->scheduled_at->format('d M Y h:i A')} is awaiting your approval.")
            ->action('Review Appointment', $url)
            ->line('Please approve or reject the appointment at your earliest convenience.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
