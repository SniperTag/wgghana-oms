<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\UserWelcomeMail;
use Illuminate\Support\Facades\Auth;


class UserService
{
    public static function createUserWithWelcomeEmail(array $data)
    {
        // Generate random password and clock-in PIN
        $randomPassword = Str::random(8);
        $randomClockinPin = rand(1000, 9999);

        // Check for duplicate email
        if (User::where('email', $data['email'])->exists()) {
            Log::warning("Attempt to create user with duplicate email: {$data['email']}");
            throw new \Exception("A user with this email already exists.");
        }

        DB::beginTransaction();

        try {
            // Create new user instance
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'] ?? null;
            $user->department_id = $data['department_id'];
            $user->supervisor_id = $data['supervisor_id'] ?? null;
            $user->is_active = $data['is_active'] ?? true;

            // Generate staff ID if not provided
            $user->staff_id = $data['staff_id'] ?? 'WG-' . strtoupper(Str::random(6));
            $user->clockin_pin = Hash::make($randomClockinPin);
            $user->pin_changed = false;
            $user->password_changed = false;
            $user->password = Hash::make($randomPassword);
            $user->face_image = $data['face_image'] ?? null;
            $user->avatar = $data['avatar'] ?? null;
            $user->is_invited = false;
            $user->save();

            // Assign roles
            if (!empty($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            // Create reset token and link
            $token = Password::createToken($user);
            $resetUrl = route('login', ['token' => $token, 'email' => $user->email]);

            // Send welcome email
            // Check for SMTP-level failures (e.g., invalid email address)
            try {
                Mail::to($user->email)->queue(new UserWelcomeMail(
                    $user,
                    $randomPassword,
                    $resetUrl,
                    $randomClockinPin
                ));

                // Log that the email was successfully queued (not sent yet)
                Log::info("✅ Welcome email successfully queued to {$user->email}");
            } catch (\Exception $ex) {
                // Log any exception during queuing (e.g., queue connection issue)
                Log::error("❌ Exception while queueing welcome email to {$user->email}: " . $ex->getMessage());
            }



            // Optionally log activity (Spatie Activitylog)
            if (function_exists('activity')) {
                activity('user')
                    ->performedOn($user)
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'roles' => $data['roles'] ?? [],
                        'staff_id' => $user->staff_id
                    ])
                    ->log('Created new user');
            }

            DB::commit();
            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Failed to create user: " . $e->getMessage());
            throw $e;
        }
    }
}
