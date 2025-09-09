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

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(string $phoneNumber, string $message)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
    }

    public function handle(SmsService $smsService): void
    {
        if (empty($this->phoneNumber) || empty($this->message)) {
            Log::warning('SMS job skipped due to empty phone or message', [
                'phone' => $this->phoneNumber,
                'message_length' => strlen($this->message)
            ]);
            return;
        }

        try {
            Log::info('Sending SMS', [
                'phone' => $this->phoneNumber,
                'message_length' => strlen($this->message)
            ]);

            $result = $smsService->sendSms($this->phoneNumber, $this->message);

            if (is_array($result) && ($result['success'] ?? false)) {
                Log::info('SMS sent successfully', [
                    'phone' => $this->phoneNumber,
                    'message_id' => $result['message_id'] ?? null
                ]);
            } else {
                $error = is_array($result) ? ($result['error'] ?? 'Unknown error') : 'Invalid response from SMS service';
                throw new \Exception($error);
            }
        } catch (\Exception $e) {
            Log::error('SMS job failed', [
                'phone' => $this->phoneNumber,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts()
            ]);

            if ($this->attempts() >= $this->tries) {
                Log::error('SMS job failed permanently', [
                    'phone' => $this->phoneNumber,
                    'attempts' => $this->attempts()
                ]);
            }

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('SMS job permanently failed', [
            'phone' => $this->phoneNumber,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);
    }
}
