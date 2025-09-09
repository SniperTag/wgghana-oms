<?php

namespace App\Services;

use App\Models\User;
use App\Models\LeaveBalance;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\UserWelcomeMail;

class UserService
{
    /**
     * Create a new user with all related records and send welcome email.
     *
     * @param array $data
     * @return User
     * @throws \Exception
     */
   public static function createUserWithWelcomeEmail(array $data): User
{
    DB::beginTransaction();

    try {
        // 1️⃣ Prepare defaults
        $randomPassword = Str::random(8);          // internal password
        $randomClockinPin = rand(1000, 9999);     // default 4-digit PIN

        // 2️⃣ Prevent duplicate emails
        if (User::where('email', $data['email'])->exists()) {
            Log::warning("Attempt to create user with duplicate email: {$data['email']}");
            throw new \Exception("A user with this email already exists.");
        }

        // 3️⃣ Create user
        $user = User::create([
            'name'             => $data['name'],
            'corporate_email'  => $data['corporate_email'] ?? $data['email'],
            'email'            => $data['email'],
            'phone'            => $data['phone'] ?? null,
            'address'          => $data['address'] ?? null,
            'nationality'      => $data['nationality'] ?? null,
            'gender'           => $data['gender'] ?? 'other',
            'department_id'    => $data['department_id'] ?? null,
            'sub_role_id'      => $data['sub_role_id'] ?? null,
            'supervisor_id'    => $data['supervisor_id'] ?? null,
            'date_of_birth'    => $data['date_of_birth'] ?? null,
            'role'          => $data['role'] ?? null,
            'password'         => Hash::make($randomPassword),
            'clockin_pin'      => $randomClockinPin,
            'pin_changed'      => false,
            'password_changed' => false,
            'face_image'       => $data['face_image'] ?? null,
            'avatar'           => $data['avatar'] ?? null,
        ]);

        // 4️⃣ Assign role
        if (!empty($data['role'])) {
            $user->assignRole($data['role']);
        }

        // 5️⃣ ID card
        if (!empty($data['id_type'])) {
            $idType = $data['id_type'] === 'other'
                ? ($data['id_type_other'] ?? null)
                : $data['id_type'];

            $user->idCards()->create([
                'card_number' => $data['card_number'] ?? null,
                'id_type'     => $idType,
            ]);
        }

        // 6️⃣ Next of Kin
        if (!empty($data['kin_name'])) {
            $relationship = ($data['kin_relationship'] ?? null) === 'other'
                ? ($data['kin_relationship_other'] ?? null)
                : ($data['kin_relationship'] ?? null);

            $user->nextOfKin()->create([
                'name'         => $data['kin_name'],
                'relationship' => $relationship,
                'phone'        => $data['kin_phone'] ?? null,
                'email'        => $data['kin_email'] ?? null,
                'address'      => $data['kin_address'] ?? null,
                'date_of_birth'=> $data['kin_date_of_birth'] ?? null,
            ]);
        }

        // 7️⃣ Emergency Contact
        if (!empty($data['emergency_name'])) {
            $relationship = ($data['emergency_relationship'] ?? null) === 'other'
                ? ($data['emergency_relationship_other'] ?? null)
                : ($data['emergency_relationship'] ?? null);

            $user->emergencyContact()->create([
                'name'         => $data['emergency_name'],
                'relationship' => $relationship,
                'phone'        => $data['emergency_phone'] ?? null,
                'email'        => $data['emergency_email'] ?? null,
                'address'      => $data['emergency_address'] ?? null,
                'age'          => $data['emergency_age'] ?? null,
            ]);
        }

        // 8️⃣ Employment Details
        $user->employmentDetail()->create([
            'employment_type' => $data['employment_type'] ?? null,
            'user_type'       => $data['user_type'] ?? 'employee',
            'department_id'   => $data['department_id'] ?? null,
            'date_of_joining' => $data['date_of_joining'] ?? null,
            'work_location'   => $data['work_location'] ?? null,
            'salary'          => $data['salary'] ?? null,
            'benefits'        => $data['benefits'] ?? null,
            'contract_duration'=> $data['contract_duration'] ?? null,
        ]);

        // 9️⃣ Leave Balances
        if (!empty($data['leave_balances']) && is_array($data['leave_balances'])) {
            foreach ($data['leave_balances'] as $leave) {
                LeaveBalance::create([
                    'user_id'       => $user->id,
                    'leave_type_id' => $leave['type_id'],
                    'total_days'    => $leave['days'],
                    'used_days'     => 0,
                    'remaining_days'=> $leave['days'],
                    'year'          => now()->year,
                ]);
            }
        }

        DB::commit();
            $resetUrl = url(route('password.change'));
        // 🔟 Optionally send email (without invite/reset link)
        try {
            // Only notify about clockin PIN or basic info
            Mail::to($user->email)->queue(new UserWelcomeMail($user, $randomPassword, $resetUrl, $randomClockinPin));
        } catch (\Throwable $e) {
            Log::error("Failed to send welcome email to {$user->email}: " . $e->getMessage());
        }

        return $user;

    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error("Failed to create user: " . $e->getMessage());
        throw $e;
    }
}

}
