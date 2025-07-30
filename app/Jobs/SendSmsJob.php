<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Services\SmsService;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $phoneNumber;
    public string $message;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The maximum number of seconds the job can run before timing out.
     */
    public int $timeout = 120;

    public function __construct(string $phoneNumber, string $message)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
    }

    public function handle(SmsService $smsService): void
    {
        try {
            Log::info('Sending SMS', [
                'phone' => $this->phoneNumber,
                'message_length' => strlen($this->message)
            ]);

            $result = $smsService->sendSms($this->phoneNumber, $this->message);

            if ($result['success']) {
                Log::info('SMS sent successfully', [
                    'phone' => $this->phoneNumber,
                    'message_id' => $result['message_id'] ?? null
                ]);
            } else {
                Log::error('SMS sending failed', [
                    'phone' => $this->phoneNumber,
                    'error' => $result['error'] ?? 'Unknown error'
                ]);
                
                throw new \Exception('SMS sending failed: ' . ($result['error'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            Log::error('SMS job failed', [
                'phone' => $this->phoneNumber,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts()
            ]);

            // If this was our last attempt, log it as a final failure
            if ($this->attempts() >= $this->tries) {
                Log::error('SMS job failed permanently', [
                    'phone' => $this->phoneNumber,
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
        Log::error('SMS job failed permanently', [
            'phone' => $this->phoneNumber,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);

        // You could send a notification to administrators here



        // or store the failed SMS in a database table for manual retry
    }
}