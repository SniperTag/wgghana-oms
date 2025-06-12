<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EarlyClockOutNotification;
use App\Notifications\MissedClockInNotification;
use App\Notifications\StaffStatusAlert;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function notifyEarlyClockOut(User $user, Carbon $time, string $note = null): void
    {
        $message = "{$user->name} checked out early at " . $time->format('H:i');

        // 🔔 Web, Email, Slack
        $admins = User::role(['Admin', 'HR'])->get();
        Notification::send($admins, new EarlyClockOutNotification($user, $time, $note));

        // 📱 SMS to Admin/HR
        foreach ($admins as $admin) {
            if ($admin->phone) {
                app(SmsService::class)->send($admin->phone, $message);
            }
        }
    }

    public function notifyMissedClockIn(User $user): void
    {
        $admins = User::role(['Admin', 'HR'])->get();
        $date = now()->toDateString();

        // Create notification instance once
        $notification = new MissedClockInNotification($date);

        // ✅ Notify staff via mail and SMS
        Notification::route('mail', $user->email)->notify($notification);

        if ($user->phone) {
            app(SmsService::class)->send($user->phone, "⏰ You missed clock-in before 12:00 PM on {$date}.");
        }

        // ✅ Notify supervisor if exists
        if ($user->supervisor) {
            $user->supervisor->notify($notification);
        }

        // ✅ Notify Admin/HR
        Notification::send($admins, $notification);
    }
    public function sendEarlyCheckoutAlert(User $user, string $checkoutTime)
    {
        $message = "{$user->name} checked out early at {$checkoutTime}.";
        // Notify all users with 'admin' or 'hr' role
        $recipients = User::role(['admin', 'hr'])->get();
        Notification::send($recipients, new StaffStatusAlert($message));
    }
    public function sendClockOutAlert(User $user, string $time)
    {
        $message = "{$user->name} clocked out at {$time}.";
        // You can customize recipients as needed, e.g., all admins/HR
        $recipients = User::role(['admin', 'hr'])->get();
        Notification::send($recipients, new StaffStatusAlert($message));
    }
    public function sendClockInAlert(User $user, string $checkInTime): void
    {
        // Example: Log or send a notification (customize as needed)
        Log::info("🔔 Clock-in alert sent to {$user->name} at {$checkInTime}");
        // You can implement actual notification logic here, e.g., email, SMS, etc.
    }
}
