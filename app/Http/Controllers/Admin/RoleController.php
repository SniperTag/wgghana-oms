<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // Display all roles with their permissions and users
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $users = User::with('permissions')->get();
        $permissions = Permission::all();

        Log::info('Roles list viewed by admin');

        return view('admin.access.partials.roles', compact('roles', 'users', 'permissions'));
    }

    // Show create role modal/page
    public function create()
    {
        $permissions = Permission::all();
        $roles = Role::all();
        $user = Auth::user();
        Log::info('Role create view accessed');

        return view('admin.roles.create', compact('permissions', 'roles', 'user'));
    }

    // Store a newly created role
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        Log::info("Role '{$role->name}' created with permissions", ['permissions' => $request->permissions]);

        toastr()->success('Role created successfully');
        return redirect()->route('roles.index');
    }
    public function show($id)
{
    $role = Role::findOrFail($id);
    return view('admin.roles.show', compact('role'));
}

    // Show the edit role form
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        Log::info("Role '{$role->name}' edit view accessed");

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    // Update role name and permissions
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $request->name]);

        // Fetch valid permissions using IDs
        $permissionIds = $request->permissions ?? [];
        $permissions = Permission::whereIn('id', $permissionIds)->get();

        // Validate permission count to avoid syncing invalid ones
        if (count($permissions) !== count($permissionIds)) {
            Log::warning("Invalid permission IDs during update for role '{$role->name}'", [
                'submitted' => $permissionIds,
                'valid' => $permissions->pluck('id')->toArray()
            ]);
            toastr()->error('Invalid permissions selected');
            return redirect()->back()->route('access.management');
        }

        $role->syncPermissions($permissions);

        Log::info("Role '{$role->name}' updated", [
            'new_name' => $request->name,
            'permissions' => $permissionIds
        ]);

        toastr()->success('Role updated successfully');
        return redirect()->route('roles.index');
    }

    // Delete a role unless it's protected
    public function destroy(Role $role)
    {
        if (in_array($role->name, ['Admin', 'Super Admin'])) {
            Log::warning("Attempt to delete core role '{$role->name}' blocked");
            return redirect()->route('roles.index')->with('error', 'You cannot delete a core role like Admin or Super Admin.');
        }

        $role->delete();

        Log::info("Role '{$role->name}' deleted");

        toastr()->success('Role deleted successfully');
        return redirect()->route('roles.index');
    }

    // Show permission assignment form for a role
    public function assignPermissions(Role $role)
    {
        $permissions = Permission::all();

        Log::info("Assign permission view accessed for role '{$role->name}'");

        return view('admin.roles.assign-permissions', compact('role', 'permissions'));
    }

    // Store assigned permissions for a role
    public function assignPermissionsStore(Request $request, Role $role)
    {
        $request->validate(['permissions' => 'array']);

        $role->syncPermissions($request->permissions ?? []);

        Log::info("Permissions assigned to role '{$role->name}'", ['permissions' => $request->permissions]);

        toastr()->success('Permissions assigned successfully');
        return redirect()->route('access.management');
    }

    // Show assign users to role form
    public function assignUsers(Role $role)
    {
        $users = User::all();

        Log::info("Assign users view accessed for role '{$role->name}'");

        return view('admin.roles.assign-users', compact('role', 'users'));
    }

    // Store user-role assignments
    public function assignUsersStore(Request $request, Role $role)
    {
        $request->validate(['users' => 'array']);

        $currentUsers = User::role($role->name)->pluck('id')->toArray();
        $newUsers = $request->users ?? [];

        // Remove role from users not selected
        $toRemove = array_diff($currentUsers, $newUsers);
        foreach ($toRemove as $userId) {
            $user = User::find($userId);
            if ($user) {
                $user->removeRole($role->name);
                Log::info("Role '{$role->name}' removed from user '{$user->name}'");
            }
        }

        // Assign role to new users
        foreach ($newUsers as $userId) {
            $user = User::find($userId);
            if ($user && !$user->hasRole($role->name)) {
                $user->assignRole($role->name);
                Log::info("Role '{$role->name}' assigned to user '{$user->name}'");
            }
        }

        toastr()->success('Users assigned to role successfully');
        return redirect()->route('roles.index');
    }

    // Show users currently holding the role
    public function removeUsers(Role $role)
    {
        $users = User::role($role->name)->get();

        Log::info("Remove users view accessed for role '{$role->name}'");

        return view('admin.roles.remove-users', compact('role', 'users'));
    }

    // Remove users from the role
    public function removeUsersStore(Request $request, Role $role)
    {
        $request->validate(['users' => 'array']);

        foreach ($request->users as $userId) {
            $user = User::find($userId);
            if ($user) {
                $user->removeRole($role->name);
                Log::info("Role '{$role->name}' removed from user '{$user->name}'");
            }
        }

        toastr()->success('Users removed from role successfully');
        return redirect()->route('roles.index');
    }
}
