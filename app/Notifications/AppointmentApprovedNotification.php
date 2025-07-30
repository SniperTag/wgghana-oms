<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentApprovedNotification extends Notification
{
    

    /**
     * Create a new notification instance.
     */
    protected $appointment;
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
        return (new MailMessage)
          ->subject('Your Appointment Has Been Approved')
            ->greeting('Hello ' . $notifiable->full_name . ',')
            ->line('Your appointment scheduled for ' . $this->appointment->scheduled_at->format('d M Y h:i A') . ' has been approved.')
            ->line('Host: ' . $this->appointment->user->name)
            ->line('Kindly check in at the reception upon arrival. and Be on time.')
            ->action('View Appointment', url(route('appointments.checkin', $this->appointment->id)))
            ->line('Thank you for using our system!');
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
