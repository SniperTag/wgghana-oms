<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\VisitLog;

class VisitorApprovalRequestNotification extends Notification
{
    use Queueable;

    public $visitLog;

    public function __construct(VisitLog $visitLog)
    {
        $this->visitLog = $visitLog;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Visitor Approval Request')
            ->greeting('Hello ' . $notifiable->name)
            ->line('A visitor is waiting for your approval.')
            ->line('Visitor: ' . $this->visitLog->visitor->name)
            ->line('Purpose: ' . $this->visitLog->purpose)
            ->action('Approve or Reject', url('/host/visit-approvals')) // update this URL
            ->line('Thank you.');
    }

    public function toArray($notifiable)
    {
        return [
            'visit_log_id' => $this->visitLog->id,
            'visitor_name' => $this->visitLog->visitor->name,
            'purpose' => $this->visitLog->purpose,
            'host_id' => $this->visitLog->host_id,
        ];
    }
}
