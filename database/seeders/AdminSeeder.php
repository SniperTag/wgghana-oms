<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SubRole;
use App\Models\LeaveType;
use App\Models\Department;
use App\Models\LeaveBalance;
use App\Models\EmergencyContact;
use App\Models\EmploymentDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents; // disables model events for speed and memory

    public function run(): void
    {
        // Increase memory limit temporarily
        ini_set('memory_limit', '2G');

        $now = now();

        // Cache IDs to avoid repeated queries
        $departmentId = Department::where('name', 'Administration')->value('id');
        if (! $departmentId) {
            $this->command->warn('⚠️ Department "Administration" not found. Skipping admin creation.');
            return;
        }

        $ceoSubRoleId = SubRole::where('name', 'ceo')->value('id');
        $leaveTypeId = LeaveType::firstOrCreate(['name' => 'Annual Leave'])->id;
        $roleId = DB::table('roles')->where('name', 'super_admin')->value('id');

        // Use a single transaction to minimize memory usage
        DB::transaction(function () use ($departmentId, $ceoSubRoleId, $leaveTypeId, $roleId, $now) {

            // Insert admin user
            $adminUserId = User::updateOrCreate(
                ['email' => 'admin@gmail.com'],
                [
                    'name' => 'System Administrator',
                    'phone' => '0244585632',
                    'role' => 'super_admin',
                    'staff_id' => 'WG-AD-0001-2025',
                    'department_id' => $departmentId,
                    'password' => Hash::make('password'),
                    'password_changed' => true,
                    'clockin_pin' => Hash::make('1234'),
                    'is_active' => true,
                    'nationality' => 'Ghanaian',
                    'date_of_birth' => '1990-01-01',
                    'corporate_email' => 'admin@wgghana.com',
                    'gender' => 'male',
                    'pin_changed' => true,
                    'sub_role_id' => $ceoSubRoleId ?? null,
                ]
            )->id;

            // Batch insert related records
            EmergencyContact::updateOrCreate(
                ['user_id' => $adminUserId],
                [
                    'name' => 'John Doe',
                    'relationship' => 'sibling',
                    'phone' => '0241234567',
                    'email' => 'john@example.com',
                ]
            );

            EmploymentDetail::updateOrCreate(
                ['user_id' => $adminUserId],
                [
                    'department_id' => $departmentId,
                    'work_location' => 'Head Office',
                    'user_type' => 'employee',
                    'date_of_joining' => $now,
                    'start_date' => $now,
                    'supervisor_id' => null,
                    'employment_status' => 'Active',
                    'employment_type' => 'fulltime',
                    'job_title' => 'Chief Executive Officer',
                    'salary' => 15000,
                    'pay_grade' => 'A1',
                    'benefits' => 'Health Insurance, Pension, Company Car',
                    'contract_duration' => 'Permanent',
                ]
            );

            LeaveBalance::updateOrCreate(
                ['user_id' => $adminUserId, 'leave_type_id' => $leaveTypeId],
                [
                    'total_days' => 30,
                    'used_days' => 0,
                    'remaining_days' => 30,
                    'year' => $now->year,
                ]
            );

            // Assign role directly in pivot table to avoid syncRoles overhead
            DB::table('model_has_roles')->updateOrInsert(
                ['model_type' => User::class, 'model_id' => $adminUserId],
                ['role_id' => $roleId]
            );
        });

        $this->command->info('✅ Admin user seeded successfully (memory-efficient).');
    }
}
