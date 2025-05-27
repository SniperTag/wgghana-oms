<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInviteMail;
use App\Models\User;

class InviteService
{
    public static function sendInvite($email)
    {
        $token = Str::uuid();

        User::create([
            'email' => $email,
            'name' => explode('@', $email)[0],
            'phone' => null,
            'department_id' => null,
            'is_active' => false,
            'is_invited' => true,
            'invite_token' => $token,
            'invite_expires_at' => now()->addDays(7),
            'password' => bcrypt(Str::random(10)),
        ]);

        Mail::to($email)->send(new UserInviteMail($token));
    }
}
