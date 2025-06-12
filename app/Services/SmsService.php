<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $senderId;

    public function __construct()
    {
        $this->baseUrl = config('services.nalo.base_url');
        $this->apiKey = config('services.nalo.api_key');
        $this->senderId = config('services.nalo.sender_id');
    }

    /**
     * Send SMS using Nalo Solutions API.
     *
     * @param string $recipientPhone
     * @param string $message
     * @return bool
     */
    public function send(string $recipientPhone, string $message): bool
    {
        try {
            $response = Http::post("{$this->baseUrl}/sms/api", [
                'key' => $this->apiKey,
                'to' => $recipientPhone,
                'msg' => $message,
                'sender_id' => $this->senderId,
            ]);

            if ($response->successful()) {
                Log::info("SMS sent to {$recipientPhone} via Nalo.");
                return true;
            }

            Log::error('Nalo SMS failed', [
                'phone' => $recipientPhone,
                'message' => $message,
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Nalo SMS Exception', [
                'exception' => $e->getMessage(),
                'phone' => $recipientPhone,
            ]);
            return false;
        }
    }
}
