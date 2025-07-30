<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class StepOutNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $action; // stepped out or returned

    public function __construct($user, $action)
    {
        $this->user = $user;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast']; // Pusher popup uses `broadcast`
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("{$this->user->name} has {$this->action}")
            ->greeting("Hello {$notifiable->name},")
            ->line("{$this->user->name} has just {$this->action} at " . now()->format('h:i A'))
            ->salutation('Regards, Waltergates Office System');
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Step Out Alert',
            'message' => "{$this->user->name} has just {$this->action}.",
            'time' => now()->toDateTimeString()
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'user' => $this->user->name,
            'action' => $this->action,
            'timestamp' => now()->toDateTimeString()
        ];
    }
}
