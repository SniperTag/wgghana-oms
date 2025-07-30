<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Flasher\Prime\Notification\Notification;
use App\Notifications\LeaveApprovedNotification;

class SendLeaveApprovalNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
     $leave = $event->leave;
     $leave->user->notify(new LeaveApprovedNotification($leave));
    }
}
