<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\LeavePolicySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run all individual seeders
        $this->call([
            DepartmentSeeder::class,
            RolePermissionSeeder::class,
            LeaveTypeSeeder::class,
            LeavePolicySeeder::class,

        ]);

        // Create or update the default Admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Match on email
            [
                'name' => 'Admin',
                'phone' => '1234567890',
                'staff_id' => 'WG-0001-2025',
                'department_id' => 1, // Must exist in DepartmentSeeder
                'password' => Hash::make('password'),
                'password_changed' => true, // Assuming the admin has changed their password
                'clockin_pin' => Hash::make('1234'),
                'is_active' => true,
                'is_invited' => false,
                'invite_token' => null,
                'invite_token_expiry' => null,
                'invite_token_used' => null,
            ]
        );

        // Assign role using Spatie
        $adminUser->assignRole('Admin');
    }
}
