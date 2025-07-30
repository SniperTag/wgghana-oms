<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $notifiable;
    public Notification $notification;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The maximum number of seconds the job can run before timing out.
     */
    public int $timeout = 120;

    public function __construct($notifiable, Notification $notification)
    {
        $this->notifiable = $notifiable;
        $this->notification = $notification;
    }

    public function handle(): void
    {
        try {
            Log::info('Sending email notification', [
                'notifiable_type' => get_class($this->notifiable),
                'notifiable_id' => $this->notifiable->id ?? null,
                'notification_type' => get_class($this->notification)
            ]);

            $this->notifiable->notify($this->notification);

            Log::info('Email notification sent successfully', [
                'notifiable_type' => get_class($this->notifiable),
                'notifiable_id' => $this->notifiable->id ?? null,
                'notification_type' => get_class($this->notification)
            ]);
        } catch (\Exception $e) {
            Log::error('Email notification job failed', [
                'notifiable_type' => get_class($this->notifiable),
                'notifiable_id' => $this->notifiable->id ?? null,
                'notification_type' => get_class($this->notification),
                'error' => $e->getMessage(),
                'attempt' => $this->attempts()
            ]);

            // If this was our last attempt, log it as a final failure
            if ($this->attempts() >= $this->tries) {
                Log::error('Email notification job failed permanently', [
                    'notifiable_type' => get_class($this->notifiable),
                    'notifiable_id' => $this->notifiable->id ?? null,
                    'notification_type' => get_class($this->notification),
                    'attempts' => $this->attempts()
                ]);
            }

            throw $e; // Re-throw to trigger retry mechanism
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Email notification job failed permanently', [
            'notifiable_type' => get_class($this->notifiable),
            'notifiable_id' => $this->notifiable->id ?? null,
            'notification_type' => get_class($this->notification),
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);

        // You could send a notification to administrators here
        // or store the failed email in a database table for manual retry
    }
}