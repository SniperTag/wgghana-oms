<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Visitor;
use App\Jobs\SendSmsJob;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Log;
use App\Notifications\VisitLogStatusNotification;
use App\Notifications\VisitorRegisteredNotification;
use App\Notifications\GroupVisitorNotification;

class NotificationService
{
    /**
     * Send notification to a single visitor.
     * Email is preferred; fallback to SMS if no email.
     */
    public function sendVisitorNotifications(Visitor $visitor): void
    {
        try {
            // 1️⃣ Notify the host if assigned
            $this->notifyHost($visitor);

            // 2️⃣ Notify the visitor
            if ($visitor->email) {
                SendEmailJob::dispatch($visitor, new VisitorRegisteredNotification($visitor, 'email'));
                Log::info('Email notification queued', [
                    'visitor_id' => $visitor->id,
                    'email' => $visitor->email
                ]);
            } elseif ($visitor->phone) {
                $message = $this->buildSmsMessage($visitor);
                SendSmsJob::dispatch($visitor->phone, $message);
                Log::info('SMS notification queued (fallback)', [
                    'visitor_id' => $visitor->id,
                    'phone' => $visitor->phone
                ]);
            } else {
                Log::warning('No contact available for visitor', [
                    'visitor_id' => $visitor->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send visitor notifications', [
                'visitor_id' => $visitor->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Notify the host about visitor arrival.
     */
    protected function notifyHost(Visitor $visitor): void
    {
        if (!$visitor->host_id) return;

        $host = User::find($visitor->host_id);

        if (!$host) {
            Log::warning('Host not found for visitor notification', [
                'visitor_id' => $visitor->id,
                'host_id' => $visitor->host_id
            ]);
            return;
        }

        try {
            $host->notify(new VisitLogStatusNotification($visitor, 'visitor'));
            Log::info('Host notification sent', [
                'visitor_id' => $visitor->id,
                'host_id' => $host->id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify host', [
                'visitor_id' => $visitor->id,
                'host_id' => $host->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send notifications for a group of visitors.
     */
    public function notifyGroupVisitorsRegistered(array $visitors): void
    {
        $admins = User::role(['Admin', 'HR'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new GroupVisitorNotification($visitors));
        }

        // Optionally, send SMS/email to visitors themselves
        foreach ($visitors as $visitor) {
            $this->sendVisitorNotifications($visitor);
        }
    }

    /**
     * Send notifications to multiple visitors (bulk).
     */
    public function sendBulkNotifications(array $visitors): void
    {
        foreach ($visitors as $visitor) {
            $this->sendVisitorNotifications($visitor);
        }
    }

    /**
     * Send a custom notification to a visitor via selected channels.
     */
    public function sendCustomNotification(Visitor $visitor, string $message, array $channels = ['email', 'sms']): void
    {
        try {
            if (in_array('email', $channels) && $visitor->email) {
                $this->sendCustomEmail($visitor, $message);
            }

            if (in_array('sms', $channels) && $visitor->phone) {
                SendSmsJob::dispatch($visitor->phone, $message);
            }

            Log::info('Custom notification sent', [
                'visitor_id' => $visitor->id,
                'channels' => $channels
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send custom notification', [
                'visitor_id' => $visitor->id,
                'channels' => $channels,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send custom email (implementation depends on your email notification class)
     */
    protected function sendCustomEmail(Visitor $visitor, string $message): void
    {
        SendEmailJob::dispatch($visitor, new VisitorRegisteredNotification($visitor, 'email', $message));
    }

    /**
     * Build SMS message for a visitor.
     */
    protected function buildSmsMessage(Visitor $visitor): string
    {
        $baseMessage = "Hello {$visitor->full_name}, you've been registered as a visitor.";
        $uidMessage = " Your UID is {$visitor->visitor_uid}.";

        if ($visitor->group_uid) {
            $groupMessage = $visitor->is_leader 
                ? " You are the group leader for group {$visitor->group_uid}."
                : " You are part of group {$visitor->group_uid}.";
            return $baseMessage . $uidMessage . $groupMessage;
        }

        return $baseMessage . $uidMessage;
    }
}
