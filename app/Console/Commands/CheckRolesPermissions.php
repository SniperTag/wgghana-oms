<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class CheckRolesPermissions extends Command
{
    protected $signature = 'check:roles-permissions';

    protected $description = 'Check roles, permissions, and example user role assignments';

    public function handle()
    {
        $this->info('--- Roles in the system ---');
        $roles = Role::all();
        if ($roles->isEmpty()) {
            $this->warn('No roles found!');
        } else {
            foreach ($roles as $role) {
                $perms = $role->permissions->pluck('name')->toArray();
                $this->line("Role: {$role->name} (guard: {$role->guard_name})");
                $this->line("  Permissions: " . implode(', ', $perms));
            }
        }

        $this->info("\n--- Permissions in the system ---");
        $permissions = Permission::all();
        if ($permissions->isEmpty()) {
            $this->warn('No permissions found!');
        } else {
            $this->line(implode(', ', $permissions->pluck('name')->toArray()));
        }

        $this->info("\n--- Example user roles ---");
        $user = User::where('email', 'admin@gmail.com')->first();
        if (!$user) {
            $this->warn('User admin@gmail.com not found!');
        } else {
            $roles = $user->getRoleNames();
            $this->line("User: {$user->name} ({$user->email})");
            $this->line("Roles: " . implode(', ', $roles->toArray()));
        }

        return 0;
    }
}
