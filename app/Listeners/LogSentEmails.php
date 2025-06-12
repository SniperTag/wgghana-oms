<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSentEmails implements ShouldQueue
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
    public function handle(MessageSent $event)
    {
        $to = implode(', ', array_column($event->message->getTo(), 'address'));
        $subject = $event->message->getSubject();
        $body = $event->message->getBody(); // raw HTML or plain text

        Log::info("📨 Email sent to: {$to} | Subject: {$subject}");
        // Optional: Log body, but may include sensitive info
        // Log::debug("Email body: " . $body);
    }
}
