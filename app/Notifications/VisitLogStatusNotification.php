<?php

namespace App\Notifications;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VisitLogStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Visitor $visitor;

    public function __construct(Visitor $visitor)
    {
        $this->visitor = $visitor;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database']; // Send via email and store in database
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('New Visitor Registration - ' . $this->visitor->full_name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new visitor has been registered and assigned to you as their host.')
            ->line('**Visitor Details:**')
            ->line('Name: ' . $this->visitor->full_name)
            ->line('Email: ' . $this->visitor->email)
            ->line('Phone: ' . ($this->visitor->phone ?: 'Not provided'))
            ->line('Company: ' . ($this->visitor->company ?: 'Not specified'))
            ->line('Visitor ID: ' . $this->visitor->visitor_uid)
            ->line('Registration Time: ' . $this->visitor->created_at->format('M d, Y \a\t h:i A'));

        // Add group information if applicable
        if ($this->visitor->group_uid) {
            $message->line('**Group Information:**')
                ->line('Group ID: ' . $this->visitor->group_uid);
            
            if ($this->visitor->is_leader) {
                $message->line('This visitor is the **Group Leader**.');
            } else {
                $message->line('This visitor is part of a group.');
            }
        }

        $message->line('Please be prepared to receive your visitor.')
            ->action('View Visitor Details', url('/admin/visitors/' . $this->visitor->id))
            ->line('Thank you!');

        return $message;
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'visitor_assigned',
            'visitor_id' => $this->visitor->id,
            'visitor_name' => $this->visitor->full_name,
            'visitor_uid' => $this->visitor->visitor_uid,
            'visitor_email' => $this->visitor->email,
            'visitor_phone' => $this->visitor->phone,
            'visitor_company' => $this->visitor->company,
            'group_uid' => $this->visitor->group_uid ?? null,
            'is_group_leader' => $this->visitor->is_leader ?? false,
            'registered_at' => $this->visitor->created_at,
            'message' => 'New visitor ' . $this->visitor->full_name . ' has been assigned to you.',
        ];
    }
}