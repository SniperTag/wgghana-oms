<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\EmergencyContact;
use App\Models\EmploymentDetail;
use App\Models\LeaveBalance;
use App\Models\Department;
use App\Models\LeaveType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ✅ Reset all permission tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // ✅ Define permissions
        $permissions = [
            'view dashboard',
            'manage employee',
            'approve leave',
            'generate payslip',
            'assign tasks',
            'review reports',
            'recommend leaves',
            'submit reports',
            'apply for leave',
            'view payslips',
            'view reports',
            'view leave',
            'manage payroll',
            'manage roles',
            'system security',
            'backups',

            // Visitor-specific
            'register visitor',
            'check in visitor',
            'check out visitor',
            'schedule appointment',
            'view visitor list',
            'print visitor badge',
            'verify visitor check-in',
            'flag visitor',
            'view emergency log',
            'monitor overstays'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // ✅ Define roles and assign permissions
        $roles = [
            'admin' => $permissions,
            'hr' => ['view dashboard', 'manage employee', 'approve leave', 'generate payslip'],
            'supervisor' => ['assign tasks', 'review reports', 'recommend leaves', 'view dashboard', 'approve leave'],
            'manager' => ['assign tasks', 'review reports', 'recommend leaves', 'view dashboard', 'approve leave'],
            'staff' => ['submit reports', 'apply for leave', 'view payslips', 'view dashboard', 'view reports', 'view leave'],
            'finance' => ['manage payroll', 'generate payslip', 'view dashboard'],
            'it-admin' => ['manage roles', 'view dashboard', 'system security', 'backups'],
            'receptionist' => ['view dashboard', 'register visitor', 'check in visitor', 'check out visitor', 'schedule appointment', 'view visitor list', 'print visitor badge'],
            'security' => ['view dashboard', 'view visitor list', 'verify visitor check-in', 'flag visitor', 'view emergency log', 'monitor overstays'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }

        // ✅ Create default admin user
        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        $department = Department::firstOrCreate(['name' => 'Administration']); // Ensure department exists

        $adminUser = User::updateOrCreate(
            ['email' => $adminEmail],
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

        // ✅ Assign admin role
        $adminUser->syncRoles(['admin']);

        // ✅ Add Emergency Contact
        EmergencyContact::updateOrCreate(
            ['user_id' => $adminUser->id],
            [
                'name' => 'John Doe',
                'relationship' => 'Brother',
                'phone' => '0241234567',
                'email' => 'john@example.com',
            ]
        );

        // ✅ Add Employment Details
        EmploymentDetail::updateOrCreate(
            ['user_id' => $adminUser->id],
            [
                'department_id' => $department->id,
                'work_location' => 'Head Office',
                'user_type' => 'employee',
                'date_of_joining' => now(),
                'supervisor_id' => null,
                'employment_status' => 'Active',
                'employment_type' => 'fulltime',
                'start_date' => now(),
                'end_date' => null,
                'job_title' => 'Administrator',
                'salary' => 5000.00,
                'pay_grade' => 'A1',
                'benefits' => 'Health Insurance, Pension',
                'contract_duration' => 'Permanent',
            ]
        );

        // ✅ Add Leave Balance
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


        $this->command->info('✅ Roles, permissions, admin user, employment details, and emergency contact seeded successfully.');
    }
}
