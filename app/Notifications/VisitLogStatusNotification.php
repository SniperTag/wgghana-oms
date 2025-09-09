<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class VisitLogStatusNotification extends Notification
{
    use Queueable;
    protected $visitor;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($visitor, $message)
    {
        $this->visitor = $visitor;
        $this->message = "A new visit has been logged for visitor: {$visitor->full_name}. Please review the visit details.";
    }
  
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if(!empty($this->visitor->email)){
            return ['mail'];
        }
        return ['sms'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
             ->subject('Visit Log Status Update')
            ->line("Hello {$notifiable->name},")
            ->line($this->message)
            ->line('Thank you.');
    }

     /**
     * To SMS.
     */
    public function toSms($notifiable)
    {
        return $this->message;
    }

   
}
