<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Visitor;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\StaffStatusAlert;
use App\Notifications\GroupVisitorNotification;
use App\Notifications\EarlyClockOutNotification;
use App\Notifications\MissedClockInNotification;
use App\Notifications\VisitorRegisteredNotification;

class NotificationService
{
    /**
     * Notify Admin/HR if staff checks out early.
     */
    public function notifyEarlyClockOut(User $user, Carbon $time, string $note = null): void
    {
        $message = "{$user->name} checked out early at " . $time->format('H:i');

        $admins = User::role(['super_admin','admin', 'manager', 'supervisor'])->get();

        // Notify via Laravel Notifications (web/email)
        Notification::send($admins, new EarlyClockOutNotification($user, $time, $note));

        // Notify via SMS
        foreach ($admins as $admin) {
            if (!empty($admin->phone)) {
                app(SmsService::class)->send($admin->phone, $message);
            }
        }

        Log::info("Early checkout notification sent for {$user->name}", [
            'time' => $time->format('H:i'), 
            'note' => $note
        ]);
    }

    /**
     * Notify missed clock-in.
     */
    public function notifyMissedClockIn(User $user): void
    {
        $admins = User::role(['super_admin','admin', 'manager', 'supervisor'])->get();
        $date = now()->toDateString();
        $notification = new MissedClockInNotification($date);

        // Notify staff
        if (!empty($user->email)) {
            Notification::route('mail', $user->email)->notify($notification);
        } elseif (!empty($user->phone)) {
            app(SmsService::class)->send($user->phone, "⏰ You missed clock-in before 12:00 PM on {$date}.");
        }

        // Notify supervisor if exists
        if ($user->supervisor) {
            $user->supervisor->notify($notification);
        }

        // Notify Admin/HR
        Notification::send($admins, $notification);

        Log::info("Missed clock-in notifications sent for {$user->name}", ['date' => $date]);
    }

    /**
     * Send early checkout alert via StaffStatusAlert notification.
     */
    public function sendEarlyCheckoutAlert(User $user, string $checkoutTime)
    {
        $message = "{$user->name} checked out early at {$checkoutTime}.";
        $recipients = User::role(['super_admin','admin', 'manager', 'supervisor'])->get();
        Notification::send($recipients, new StaffStatusAlert($message));
        Log::info("StaffStatusAlert sent for early checkout", ['user' => $user->id]);
    }

    /**
     * Send clock-out alert via StaffStatusAlert notification.
     */
    public function sendClockOutAlert(User $user, string $time)
    {
        $message = "{$user->name} clocked out at {$time}.";
        $recipients = User::role(['super_admin','admin', 'manager', 'supervisor'])->get();
        Notification::send($recipients, new StaffStatusAlert($message));
        Log::info("StaffStatusAlert sent for clock-out", ['user' => $user->id]);
    }

    /**
     * Send clock-in alert (currently logs only, can be extended).
     */
    public function sendClockInAlert(User $user, string $checkInTime): void
    {
        Log::info("Clock-in alert for {$user->name} at {$checkInTime}");
        // You can implement email/SMS notifications here if needed
    }

    /**
     * Notify Admin/HR of a new visitor registration.
     * Uses email first, falls back to SMS if no email.
     */
    public function notifyNewVisitorRegistered(Visitor $visitor): void
    {
        $admins = User::role(['super_admin','admin', 'manager', 'supervisor'])->get();

        foreach ($admins as $admin) {
            if (!empty($admin->email)) {
                Notification::route('mail', $admin->email)
                    ->notify(new VisitorRegisteredNotification($visitor));
            } elseif (!empty($admin->phone)) {
                $message = "New visitor registered: {$visitor->full_name}, UID: {$visitor->visitor_uid}";
                app(SmsService::class)->send($admin->phone, $message);
            }
        }

        Log::info("Visitor registration notification sent", ['visitor_id' => $visitor->id]);
    }

    /**
     * Notify Admin/HR of multiple group visitors.
     */
    public function notifyGroupVisitorsRegistered(array $visitors): void
    {
        $admins = User::role(['super_admin','admin', 'manager', 'supervisor'])->get();
        Notification::send($admins, new GroupVisitorNotification($visitors));
        Log::info("Group visitor notifications sent", ['count' => count($visitors)]);
    }
}
