<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $user;
    public $password;
    public $resetUrl;
public $staff_id;
    public $clockin_pin;
    /**
     * Create a new message instance.
     */
    public function __construct($user, $password, $resetUrl, $clockin_pin)
    {
        $this->user = $user;
        $this->password = $password;
        $this->clockin_pin = $clockin_pin;
        $this->resetUrl = $resetUrl;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to WG Office Management System',
        );
    }

    /**
     * Get the message content definition.
     */

     public function build()
    {
        return $this->subject('Welcome to ' . config('app.name'))
                    ->view('emails.user_welcome')
                    ->with([
                        'user' => $this->user,
                        'staff_id' => $this->user->staff_id,
                        'resetUrl' => $this->resetUrl,
                        'password' => $this->password,
                        'clockin_pin' => $this->clockin_pin,

                    ]);
    }
    /** @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
