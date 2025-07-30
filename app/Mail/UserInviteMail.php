<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;


class UserInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $token;

    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You are Invited!',
        );
    }

    public function content(): Content
{
    return new Content(
        view: 'emails.user_invite',
        with: [
            'token' => $this->token,
        ],
    );
}

    public function attachments(): array
    {
        return [];
    }
}