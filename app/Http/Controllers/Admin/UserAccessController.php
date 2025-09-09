<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Department;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserAccessController extends Controller
{
    /**
     * Display all users with their permissions, roles, and departments
     */
    public function index()
    {
        $users = Auth::user()->getAllUsersWithPermissions();
        $roles = Role::with('permissions')->get();
        $departments = Department::all();
        $permissions = Permission::where('guard_name', 'web')->get();

        Log::info('Access management view accessed');

        return view('admin.access.management', compact('users', 'permissions', 'roles', 'departments'));
    }

    /**
     * Assign (sync) permissions to a user
     */
public function givePermission(Request $request, User $user)
{
    $request->validate([
        'permissions' => 'required|array',
        'permissions.*' => 'exists:permissions,id', // validates IDs
    ]);

    // Fetch Permission models by ID
    $permissions = Permission::whereIn('id', $request->permissions)->get();

    // Sync permissions using Permission models, NOT IDs
    $user->syncPermissions($permissions);

    \Log::info('Permissions updated for user', [
        'user_id' => $user->id,
        'permissions' => $permissions->pluck('name')->toArray(),
    ]);

    toastr()->success('Permissions updated successfully.');

    return redirect()->back();
}


    /**
     * Revoke a single permission from a user
     */
    public function revokePermission(User $user, Permission $permission)
    {
        if ($permission->guard_name !== 'web') {
            toastr()->error('Cannot revoke permission with a different guard.');
            return redirect()->back();
        }

        if ($user->hasPermissionTo($permission)) {
            $user->revokePermissionTo($permission);
        }

        Log::info('Permission revoked', [
            'user_id' => $user->id,
            'permission' => $permission->name,
        ]);

        toastr()->success('Permission revoked successfully.');
        return redirect()->back();
    }

    /**
     * Revoke multiple permissions from a user
     */
    public function revokeMultiplePermissions(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user = User::findOrFail($request->user_id);

        $permissions = Permission::whereIn('id', $request->permissions)
                                 ->where('guard_name', 'web')
                                 ->get();

        foreach ($permissions as $permission) {
            $user->revokePermissionTo($permission);
        }

        Log::info('Multiple permissions revoked', [
            'user_id' => $user->id,
            'permissions' => $permissions->pluck('name')->toArray(),
        ]);

        toastr()->success('Permissions revoked successfully.');
        return redirect()->back();
    }

    /**
     * Revoke all permissions from a user
     */
    public function revokeAllPermissions(User $user)
    {
        // Detach all direct permissions for guard 'web'
        $user->permissions()->where('guard_name', 'web')->detach();

        Log::info("All permissions revoked for user: {$user->id} ({$user->name}) by " . auth::id());

        toastr()->success('All permissions revoked successfully.');
        return redirect()->back();
    }
}
