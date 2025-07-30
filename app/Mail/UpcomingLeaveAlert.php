<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\LeaveRequest;
use App\Models\Leave;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UpcomingLeaveAlert extends Mailable
{
    use Queueable, SerializesModels;
    public $leave;

    /**
     * Create a new message instance.
     */
    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    /**
     * Get the message envelope.
     */
   public function build()
    {
        return $this->subject('Leave Notification: Your Leave Starts Tomorrow')
            ->view('emails.upcoming-leave-alert');
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
