<?php

namespace App\Services;

use App\Models\User;
use App\Models\Visitor;
use App\Jobs\SendSmsJob;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Log;
use App\Notifications\VisitLogStatusNotification;
use App\Notifications\VisitorRegisteredNotification;

class NotificationService
{
    public function sendVisitorNotifications(Visitor $visitor): void
    {
        try {
            // Notify host if assigned
            $this->notifyHost($visitor);
            
            // Send email notification to visitor
            $this->sendEmailNotification($visitor);
            
            // Send SMS notification to visitor
            $this->sendSmsNotification($visitor);
            
        } catch (\Exception $e) {
            Log::error('Failed to send visitor notifications', [
                'visitor_id' => $visitor->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    protected function notifyHost(Visitor $visitor): void
    {
        if (!$visitor->host_id) {
            return;
        }

        $host = User::find($visitor->host_id);
        if (!$host) {
            Log::warning('Host not found for visitor notification', [
                'visitor_id' => $visitor->id,
                'host_id' => $visitor->host_id
            ]);
            return;
        }

        try {
            $host->notify(new VisitLogStatusNotification($visitor));
            
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

    protected function sendEmailNotification(Visitor $visitor): void
    {
        if (!$visitor->email) {
            return;
        }

        try {
            // Queue email notification
            SendEmailJob::dispatch($visitor, new VisitorRegisteredNotification($visitor));
            
            Log::info('Email notification queued', [
                'visitor_id' => $visitor->id,
                'email' => $visitor->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to queue email notification', [
                'visitor_id' => $visitor->id,
                'email' => $visitor->email,
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function sendSmsNotification(Visitor $visitor): void
    {
        if (!$visitor->phone) {
            return;
        }

        try {
            $message = $this->buildSmsMessage($visitor);
            
            // Queue SMS notification
            SendSmsJob::dispatch($visitor->phone, $message);
            
            Log::info('SMS notification queued', [
                'visitor_id' => $visitor->id,
                'phone' => $visitor->phone
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to queue SMS notification', [
                'visitor_id' => $visitor->id,
                'phone' => $visitor->phone,
                'error' => $e->getMessage()
            ]);
        }
    }

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

    public function sendBulkNotifications(array $visitors): void
    {
        foreach ($visitors as $visitor) {
            $this->sendVisitorNotifications($visitor);
        }
    }

    public function sendCustomNotification(Visitor $visitor, string $message, array $channels = ['email', 'sms']): void
    {
        try {
            if (in_array('email', $channels) && $visitor->email) {
                // Send custom email notification
                $this->sendCustomEmail($visitor, $message);
            }

            if (in_array('sms', $channels) && $visitor->phone) {
                // Send custom SMS notification
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

    protected function sendCustomEmail(Visitor $visitor, string $message): void
    {
        // Implementation for custom email notification
        // This would depend on your specific email notification class
    }
}