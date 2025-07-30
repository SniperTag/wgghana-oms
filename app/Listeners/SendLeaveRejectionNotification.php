<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveRejectionNotification; // Make sure this is the correct notification class

class SendLeaveRejectionNotification
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
        // Notify the user about the leave rejection
        Notification::route('mail', $leave->user->email)
            ->notify(new LeaveRejectionNotification($leave));

    }
}
