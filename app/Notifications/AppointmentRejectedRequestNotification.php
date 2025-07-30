<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentRejectedRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The appointment instance.
     *
     * @var \App\Models\Appointment
     */
    protected $appointment;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Appointment $appointment
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail']; // You can add 'database' if needed
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param object $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        $scheduledDate = $this->appointment->scheduled_at->format('d M Y h:i A');
        $reason = $this->appointment->rejection_reason ?? 'No reason provided.';
        
        return (new MailMessage)
            ->subject('Your Appointment Has Been Rejected')
            ->greeting('Hello ' . $notifiable->full_name)
            ->line("Unfortunately, your appointment scheduled for **{$scheduledDate}** has been rejected.")
            ->line("**Reason:** {$reason}")
            ->line('Please contact the office if you have any questions.')
            ->action('View Appointment', url('/appointments/' . $this->appointment->id))
            ->salutation('Thank you for your understanding.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param object $notifiable
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'status' => 'rejected',
            'reason' => $this->appointment->rejection_reason,
            'scheduled_at' => $this->appointment->scheduled_at,
        ];
    }
}
