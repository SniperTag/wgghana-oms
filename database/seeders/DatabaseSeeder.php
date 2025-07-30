<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\EmergencyContact;
use App\Models\EmploymentDetail;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

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
            VisitorTypeSeeder::class,
        ]);

        // Create or update the default Admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'dn326045@gmail.com'], // Match on email
            [
                'name' => 'Daniel Nelson',
                'phone' => '0244585632',
                'staff_id' => 'WG-0001-2025',
                'password' => Hash::make('password'),
                'password_changed' => true,
                'clockin_pin' => Hash::make('1234'),
                'is_active' => true,
                'nationality' => 'Ghanaian',
                'date_of_birth' => '1990-01-01',
                'corporate_email' => 'daniel.nelson@wgghana.com',
                'gender' => 'male',
                'avatar' => asset('images/danny.jpg'), // Path to default avatar
                'pin_changed' => true,
                'face_image' => null,
            ]
        );

        // Create Emergency Contact for Admin
        EmergencyContact::updateOrCreate(
            ['user_id' => $adminUser->id], // Prevent duplicate
            [
                'name' => 'John Doe',
                'relationship' => 'Brother',
                'phone' => '0241234567',
                'email' => 'john@example.com',
            ]
        );

        EmploymentDetail::updateOrCreate(
            ['user_id' => $adminUser->id], // Prevent duplicate
            [
                'department_id' => 1, // Assuming department ID 1 exists
                'work_location' => 'Head Office',
                'user_type' => 'employee', // Default user type
                'date_of_joining' => now(), // Current date
                'supervisor_id' => null, // No supervisor for admin
                'employment_status' => 'Active',
                'employment_type' => 'fulltime',
                'start_date' => now(),
                'end_date' => null, // Ongoing employment
                'job_title' => 'Administrator',
                'salary' => 5000.00,
                'pay_grade' => 'A1',
                'benefits' => 'Health Insurance, Pension',
                'contract_duration' => 'Permanent',
            ]
        );

        $leaveType = LeaveType::firstOrCreate(['name' => 'Annual Leave']);

        LeaveBalance::updateOrCreate(
            [
                'user_id' => $adminUser->id,
                'leave_type_id' => $leaveType->id
            ],
            [
                'total_days' => 30,
                'used_days' => 0,
                'remaining_days' => 30,
                'year' => now()->year,
            ]
        );



        // Assign role using Spatie
        $adminUser->assignRole('admin');
    }
}
