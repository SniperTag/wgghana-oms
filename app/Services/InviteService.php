<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\UserInviteMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class InviteService
{
    public static function sendInvite(User $user)
    {
        $token = $user->invite_token ?? Str::uuid();
        Mail::to($user->email)->send(new UserInviteMail($user, $token));
    }

    public static function createInvitedUser(string $email)
    {
        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            throw new \Exception("User with email {$email} already exists.");
        }

        // Generate staff_id - adjust your logic if needed
        $lastUser = User::orderBy('id', 'desc')->first();
        if ($lastUser && $lastUser->staff_id) {
            $number = (int) filter_var($lastUser->staff_id, FILTER_SANITIZE_NUMBER_INT);
            $staffId = 'WG' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $staffId = 'WG-' . strtoupper(Str::random(6));
        }
        $clockin_pin = Hash::make(rand(1111, 9999));
        $token = Str::uuid();

        $user = User::create([
            'email' => $email,
            'name' => explode('@', $email)[0],
            'phone' => null,
            'department_id' => null,
            'is_active' => false,
            'is_invited' => true,
            'invite_token' => $token,
            'invite_expires_at' => now()->addDays(7),
            'staff_id' => $staffId,
            'clock_pin'=> $clockin_pin,
            'password' => bcrypt(Str::random(8)), // temporary password
        ]);

        if (!empty($roles)) {
            $user->syncRoles($roles);  // Using Spatie permission package
        }

        Mail::to($email)->send(new UserInviteMail($user, $token));

        return $user;
    }
}
