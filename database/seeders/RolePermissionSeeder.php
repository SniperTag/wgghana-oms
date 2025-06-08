<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
  public function run(): void
{
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    DB::table('roles')->where('guard_name', '!=', 'web')->update(['guard_name' => 'web']);
    DB::table('permissions')->where('guard_name', '!=', 'web')->update(['guard_name' => 'web']);

    DB::table('model_has_permissions')->delete();
    DB::table('model_has_roles')->delete();
    DB::table('role_has_permissions')->delete();

    Permission::query()->delete();
    Role::query()->delete();

    $permissions = [
        'view reports',
        'view dashboard',
        'assign tasks',
        'submit reports',
        'review reports',
        'apply for leave',
        'approve leave',
        'manage payroll',
        'generate payslip',
        'manage staffs',
        'manage roles',
        'manage employee',
        'recommend leaves',
        'view payslips',
        'system security',
        'backups',
        'view leave',
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate([
            'name' => $permission,
            'guard_name' => 'web',
        ]);
    }

    $roles = [
        'admin' => $permissions,
        'hr' => [ 'view dashboard', 'manage employee', 'approve leave', 'generate payslip'],
        'supervisor' => ['assign tasks', 'review reports', 'recommend leaves', 'view dashboard','approve leave'],
        'manager' => ['assign tasks', 'review reports', 'recommend leaves', 'view dashboard','approve leave'],
        'staff' => ['submit reports', 'apply for leave', 'view payslips', 'view dashboard','view reports', 'view leave'],
        'finance' => ['manage payroll', 'generate payslip', 'view dashboard'],
        'it-admin' => ['manage roles', 'view dashboard', 'system security', 'backups'],
    ];

    foreach ($roles as $roleName => $rolePermissions) {
        $role = Role::updateOrCreate(
            ['name' => $roleName, 'guard_name' => 'web']
        );
        $role->syncPermissions($rolePermissions);

        $this->command->info("Created role {$roleName} with permissions: " . implode(', ', $rolePermissions));
    }

    $user = User::firstOrCreate(
        ['email' => 'admin@gmail.com'],
        [
            'name' => 'admin',
            'phone' => '1234567890',
            'password' => bcrypt('password'),
            'password_changed' => true, // Assuming the admin has changed their password
            'staff_id' => 'WG-0001-2025',
            'clockin_pin' => bcrypt('1234'),
            'is_active' => true,
            'is_invited' => false,
            'invite_token' => null,
            'invite_token_expiry' => null,
            'invite_token_used' => null,
            'department_id' => 6,
        ]
    );

    $user->syncRoles(['admin', 'hr']);

    $this->command->info('✅ Roles, permissions, and default admin user seeded successfully.');
}
    /**
     * @return array
     */
    public function getPermissions(): array
    {
        return [

            'view dashboard',
            'assign tasks',
            'submit reports',
            'review reports',
            'apply for leave',
            'approve leave',
            'manage payroll',
            'generate payslip',
            'manage staffs',
            'manage roles',
            'manage employee',
            'recommend leaves',
            'view payslips',
            'system security',
            'backups',
            'view leave',
            'view reports',
        ];
    }
}
