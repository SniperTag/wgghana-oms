<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Department;
use Illuminate\Support\Facades\Log;
use Auth;
class UserAccessController extends Controller
{
    public function index()
    {
        $users = Auth::user()->getAllUsersWithPermissions();
        // Fetch all roles with their permissions
        $roles = Role::with('permissions')->get();
        
        $departments = Department::all();
        $permissions = Permission::all();

        return view('admin.access.management', compact('users', 'permissions', 'roles', 'departments'));
    }

   public function givePermission(Request $request, User $user)
{
    $request->validate([
        'permission' => 'required|exists:permissions,name',
    ]);

    if (!$user->hasPermissionTo($request->permission)) {
        $user->givePermissionTo($request->permission);
    }

    Log::info('Permission granted', [
        'user_id' => $user->id,
        'permission' => $request->permission,
    ]);

    toastr()->success('Permission granted successfully.');

    return redirect()->back(); // Redirect to previous page with success
}


    public function revokePermission(User $user, Permission $permission)
    {
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
}
