<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    // Display all permissions
    public function index()
    {
        $permissions = Permission::all();
        Log::info('Viewing all permissions');
        return view('admin.permissions.index', compact('permissions'));
    }

    // Show form to create a permission
    public function create()
    {
        return view('admin.permissions.create' );
    }

    // Store a new permission
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        Log::info("Permission created: {$permission->name}");

        toastr()->success('Permission created successfully');
        return redirect()->route('permissions.index');
    }

    // Show edit form
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    // Update permission
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $oldName = $permission->name;
        $permission->update(['name' => $request->name]);

        Log::info("Permission updated: {$oldName} to {$permission->name}");

        toastr()->success('Permission updated successfully');
        return redirect()->route('permissions.index');
    }

    // Delete permission
    public function destroy(Permission $permission)
    {
        $name = $permission->name;
        $permission->delete();

        Log::warning("Permission deleted: {$name}");

        toastr()->success('Permission deleted successfully');
        return redirect()->route('permissions.index');
    }
}
