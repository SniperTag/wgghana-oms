<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SubRole;
use App\Models\LeaveType;
use App\Models\Department;
use App\Models\LeaveBalance;
use Illuminate\Database\Seeder;
use App\Models\EmergencyContact;
use App\Models\EmploymentDetail;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Providers\DepartmentStructure;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Increase memory limit for this seeder
        ini_set('memory_limit', '1024M');

        // Clear Spatie cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Wrap everything in a transaction for better performance

            // Reset relevant tables
            $this->resetTables();

            // Create permissions
            $this->createPermissions();

            // Create main roles
            $this->createMainRoles();

            // Create departments and sub-roles
            $this->createDepartmentsAndSubRoles();



        $this->command->info('✅ Roles, permissions, sub-roles, main roles, and admin user seeded successfully.');
    }

    private function resetTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $tables = [
            'roles', 'permissions', 'model_has_roles', 'model_has_permissions', 'role_has_permissions',
            'sub_roles', 'users', 'emergency_contacts', 'employment_details', 'leave_balances'
        ];
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function createPermissions(): void
    {
        $permissions = [
            'view dashboard', 'view employees','create employee','edit employee','delete employee','manage employee','view employee details',
            'apply for leave','approve leave','reject leave','view leave','manage leave types','view leave history','recommend leaves',
            'manage payroll','generate payslip','view payslips','approve payroll',
            'view reports','generate reports','submit reports','review reports','generate client reports',
            'assign tasks','view tasks','complete tasks','review tasks',
            'register visitor','check in visitor','check out visitor','schedule appointment','view visitor list','print visitor badge','verify visitor check-in','flag visitor','view emergency log','monitor overstays',
            'manage roles','system security','backups','manage permissions','view system logs',
            'deploy code','develop software','design interface','review usability','manage website','manage database',
            'approve loans','manage client loans','view financial reports',
            'plan strategy','review strategy','manage clients',
            'assign training','assist training','manage training programs',
            'manage calendar','schedule meetings','view calendar',
            'approve budgets','approve operations','fix bugs','create mockups','monitor servers',
            'manage IT team','provide technical support','manage tickets','manage network','configure routers and switches',
            'backup database','optimize database performance','manage cloud infrastructure','monitor security','conduct audits','respond to incidents',
            'view executive reports','approve major policies','oversee CEO performance','long-term governance',
            'assist reporting','view HR reports','approve IT projects','support finance operations','analyze financial data',
            'process loan applications','update client loan records','support campaigns','conduct research','convert leads',
            'supervise team','report to manager','manage reception staff schedule','generate visitor reports','assist VIP guests','coordinate logistics','manage hospitality requests',
            'screen visitors','verify ID','deny access','escort visitor','approve incident logs','plan security strategy','access all security data',
            'report incidents','monitor CCTV','alert supervisor','generate monitoring reports','manage access cards','approve entry','deny entry','log emergency drills',
            'manage legal team','approve contracts','review compliance reports','advise management','access all legal documents','draft contracts','advise departments','manage junior lawyers','oversee litigation','handle compliance','maintain legal records','assist paralegal','draft basic documents',
            'manage client accounts','assign client officers','handle escalations','review service quality','manage assigned clients','track client satisfaction','resolve client issues','coordinate with departments','manage onboarding','track product usage','provide training','recommend solutions','log client feedback','respond to client inquiries','schedule client meetings','update client records','respond to support tickets','assist with troubleshooting',
        ];

        // Batch insert permissions for better performance
        $permissionData = [];
        foreach ($permissions as $permission) {
            $permissionData[] = [
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Use insert ignore to avoid duplicates
        DB::table('permissions')->insertOrIgnore($permissionData);

        // Clear array to free memory
        unset($permissionData);
    }

    private function createMainRoles(): void
    {
        $roles = ['super_admin','admin','manager','supervisor','team_lead','receptionist','staff','intern'];

        // Batch insert roles
        $roleData = [];
        foreach ($roles as $role) {
            $roleData[] = [
                'name' => $role,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('roles')->insertOrIgnore($roleData);
        unset($roleData);
    }

    private function createDepartmentsAndSubRoles(): void
    {
        // Cache all permissions once at the start
        $allPermissions = Permission::pluck('id', 'name')->toArray();

        $departments = DepartmentStructure::get();

        foreach ($departments as $deptName => $deptData) {
           $department = Department::firstOrCreate(
    ['name' => $deptName],
    [
        'code' => strtoupper(substr($deptName, 0, 3)), // generate a code from the name
        'description' => $deptData['description'] ?? $deptName,
        'head_id' => $deptData['head_id'] ?? null,
        'created_at' => now(),
        'updated_at' => now(),
    ]
);
            // Process sub-roles in smaller chunks
            $subRoleChunks = array_chunk($deptData['sub_roles'], 10, true);

            foreach ($subRoleChunks as $chunk) {
                $this->processSubRoleChunk($chunk, $department, $allPermissions);

                // Force garbage collection after each chunk
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }
            }
        }

        // Clear cached data
        unset($allPermissions, $departments);
    }

    private function processSubRoleChunk(array $subRoles, $department, array $allPermissions): void
    {
        foreach ($subRoles as $subKey => $subData) {
            $subRoleModel = SubRole::updateOrCreate(
                ['name' => $subKey],
                [
                    'title' => $subData['title'],
                    'department_id' => $department->id,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Attach permissions efficiently
            if (!empty($subData['permissions'])) {
                $permissionIds = [];
                foreach ($subData['permissions'] as $permissionName) {
                    if (isset($allPermissions[$permissionName])) {
                        $permissionIds[] = $allPermissions[$permissionName];
                    }
                }

                if (!empty($permissionIds)) {
                    $subRoleModel->permissions()->sync($permissionIds);
                }

                unset($permissionIds);
            }
        }
    }



}
