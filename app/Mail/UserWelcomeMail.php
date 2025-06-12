<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;  // for queueing
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $resetUrl;
    public $clockinPin;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $password, string $resetUrl, string $clockinPin)
    {
        $this->user = $user;
        $this->password = $password;
        $this->resetUrl = $resetUrl;
        $this->clockinPin = $clockinPin;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Welcome to Waltergates Office System')
                    ->view('emails.user_welcome')
                    ->with([
                        'user' => $this->user,
                        'password' => $this->password,
                        'resetUrl' => $this->resetUrl,
                        'clockinPin' => $this->clockinPin,
                    ]);
    }
}
