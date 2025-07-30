<?php

namespace App\Notifications;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VisitorRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Visitor $visitor;

    public function __construct(Visitor $visitor)
    {
        $this->visitor = $visitor;
    }

    public function via($notifiable): array
    {
        return ['mail']; // You can add 'sms' if using Twilio, etc.
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Welcome to Waltergates Ghana Limited Visitors System')
            ->greeting('Hello ' . $this->visitor->full_name . ',')
            ->line('You have been successfully registered as a visitor at Waltergates Ghana Limited.')
            ->line('**Registration Details:**')
            ->line('Name: ' . $this->visitor->full_name)
            ->line('Email: ' . $this->visitor->email)
            ->line('Phone: ' . ($this->visitor->phone ?: 'Not provided'))
            ->line('Visitor ID: ' . $this->visitor->visitor_uid) // Fixed: was visit_uid
            ->line('Company: ' . ($this->visitor->company ?: 'Not specified'));

        // Add group information if applicable
        if ($this->visitor->group_uid) {
            $message->line('Group ID: ' . $this->visitor->group_uid);
            
            if ($this->visitor->is_leader) {
                $message->line('**You are designated as the Group Leader.**');
            }
        }

        // Add host information if available
        if ($this->visitor->host_id && $this->visitor->host) {
            $message->line('Your host: ' . $this->visitor->host->name);
        }

        $message->line('Please wait to be checked in by our reception staff.')
            ->line('Thank you for visiting Waltergates Ghana Limited!')
            ->salutation('Best regards, Waltergates Ghana Limited Team');

        return $message;
    }

    /**
     * Get the array representation of the notification for database storage
     */
    public function toArray($notifiable): array
    {
        return [
            'visitor_id' => $this->visitor->id,
            'visitor_name' => $this->visitor->full_name,
            'visitor_uid' => $this->visitor->visitor_uid,
            'message' => 'New visitor registered: ' . $this->visitor->full_name,
            'group_uid' => $this->visitor->group_uid ?? null,
            'is_leader' => $this->visitor->is_leader ?? false,
        ];
    }
}