<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
 
    protected $sender;
    protected $key;

    public function __construct()
    {
       $this->key = config('services.bulksmsgh.key');
        $this->sender = config('services.bulksmsgh.sender_id');
    }

    public function send($recipient, $message)
    {
        $response = Http::post('https://clientlogin.bulksmsgh.com/sms/api', [
            'key'       => $this->key,
            'to'        => $recipient,
            'msg'       => $message,
            'sender_id' => $this->sender,
        ]);

         $code = trim($response->body()); // e.g. "1000"
        $message = $this->interpretResponseCode($code);

         // Log result
        Log::info("SMS to {$recipient}: [{$code}] {$message}");

        return [
            'code' => $code,
            'status' => $message,
            'success' => $code === '1000' || $code === '1007', // success or scheduled
        ];
    }

    protected function interpretResponseCode(string $code): string
    {
        return match ($code) {
            '1000' => '✅ Message submitted successfully.',
            '1002' => '❌ SMS sending failed.',
            '1003' => '❌ Insufficient balance.',
            '1004' => '❌ Invalid API key.',
            '1005' => '❌ Invalid phone number.',
            '1006' => '❌ Invalid sender ID (max 11 characters).',
            '1007' => '⏳ Message scheduled for later delivery.',
            '1008' => '❌ Empty message content.',
            default => '❓ Unknown response code.',
        };
    }
}


