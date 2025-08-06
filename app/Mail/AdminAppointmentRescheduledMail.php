<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminAppointmentRescheduledMail extends Mailable
{
    use Queueable, SerializesModels;
public $appointment;
public $oldDateTime;
    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment, $oldDateTime)
    {
        $this->appointment = $appointment;
        $this->oldDateTime = $oldDateTime;
    }       
  
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin Appointment Rescheduled Mail',
        );
    }

      public function build()
    {
        return $this->subject('Appointment Rescheduled')
                    ->markdown('emails.admin.appointment_rescheduled');
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
