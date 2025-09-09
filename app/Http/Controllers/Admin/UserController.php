<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\User;
use App\Models\Leave;
use App\Models\SubRole;
use App\Models\LeaveType;
use App\Models\Department;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\EmploymentDetail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Display list of users
    public function indexUser()
    {
        $users = User::with(['roles', 'department'])
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        $userCount = User::count();
        $roles = Role::all();
        $departments = Department::all();
        $permissions = Permission::all();

        return view('admin.users.index', compact('users', 'userCount', 'roles', 'departments', 'permissions'));
    }

    // Show create user form
    public function createUser()
    {
        $users = Auth::user();
        $roles = Role::all()->pluck('name');
        $userCount = User::count();
        $departments = Department::all();
        $leaveTypes = LeaveType::where('is_excluded', false)->get();
        $femaleCount = User::where('gender','female')->count();
        $maleCount = User::where('gender', 'male')->count();
        $staffCount = EmploymentDetail::where('user_type', 'staff')->count();
        $internCount = EmploymentDetail::where('user_type', 'intern')->count();
        $employeeCount = EmploymentDetail::where('user_type', 'employee')->count();
        $nationalServiceCount = EmploymentDetail::where('user_type', 'national_service')->count();
        $pendingCount = Leave::where('user_id', $users->id)->where('status', 'pending')->count();
        $supervisors = User::where('role', 'supervisor')->get();

        $subRoles = SubRole::all()
            ->groupBy('department_id')
            ->map(function($subs) {
                return $subs->pluck('title', 'id')->mapWithKeys(fn($title, $id) => [(int)$id => $title]);
            })
            ->mapWithKeys(fn($subs, $deptId) => [(int)$deptId => $subs]);

        return view('admin.users.create', compact(
            'roles', 'userCount', 'departments', 'users', 'leaveTypes', 'femaleCount',
            'maleCount', 'staffCount', 'nationalServiceCount', 'subRoles', 'internCount',
            'employeeCount', 'pendingCount','supervisors'
        ));
    }

    // Fetch sub-roles by department
    public function byDepartment($departmentId)
    {
        $subRoles = SubRole::where('department_id', $departmentId)
            ->orderBy('title')
            ->get(['id', 'title']);

        return response()->json($subRoles);
    }

    // Store new user
    public function storeUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'corporate_email' => 'nullable|email|max:255',
                'email' => 'required|email|unique:users,email',
                'gender'=> 'required|in:male,female,other',
                'user_type' => 'required|in:employee,national_service,intern',
                'date_of_birth' => 'required|date',
                'phone' => 'required|string|max:20|unique:users,phone',
                'department_id' => 'required|exists:departments,id',
                'nationality' => 'required|string|max:100',
                'id_type' => 'nullable|in:passport,national_id,voters_id,student_id,other',
                'id_type_other' => 'nullable|string|max:50|required_if:id_type,other',
                'card_number' => 'nullable|string|max:50|unique:id_cards,card_number',
                'supervisor_id' => 'nullable|exists:users,id',
                'address' => 'nullable|string|max:255',
                'face_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'role' => 'required|string|in:super_admin,admin,hr,manager,finance,supervisor,staff',
                'sub_role_id' => 'nullable|integer|exists:sub_roles,id',
                'leave_type_id' => 'nullable|exists:leave_types,id',
                'leave_days' => 'nullable|integer|min:0',
                'kin_name' => 'nullable|string|max:255',
                'kin_relationship' => 'nullable|string|max:50|in:parent,child,spouse,sibling,friend,other',
                'kin_relationship_other' => 'nullable|string|max:50|required_if:kin_relationship,other',
                'kin_phone' => 'nullable|string|max:20',
                'kin_email' => 'nullable|email|max:255',
                'kin_address' => 'nullable|string|max:255',
                'kin_date_of_birth' => 'nullable|date',
                'emergency_name' => 'nullable|string|max:255',
                'emergency_relationship' => 'nullable|string|max:50|in:parent,spouse,sibling,friend,other',
                'emergency_relationship_other' => 'nullable|string|max:50|required_if:emergency_relationship,other',
                'emergency_phone' => 'nullable|string|max:20',
                'emergency_email' => 'nullable|email|max:255',
                'emergency_address' => 'nullable|string|max:255',
                'emergency_age' => 'nullable|integer|min:18',
                'employment_type' => 'required|string|in:fulltime,parttime,contract',
                'date_of_joining' => 'nullable|date',
                'work_location' => 'nullable|string|max:255',
                'salary' => 'nullable|numeric|min:0',
                'benefits' => 'nullable|string|max:500',
                'contract_duration' => 'nullable|string|max:100',
            ]);


        $data = $validated;

            // Handle file uploads
        if ($request->hasFile('face_image')) {
            $data['face_image'] = $request->file('face_image')->store('face_images', 'public');
        }
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }


            $user = UserService::createUserWithWelcomeEmail($data);

        toastr()->success("User created successfully!");
            return redirect()->route('all.staffs');

        } catch (ValidationException $e) {
        // Loop through all validation errors and display via Toastr
        foreach ($e->errors() as $fieldErrors) {
            foreach ($fieldErrors as $error) {
                toastr()->error($error);
            }
        }
        return back()->withInput();

    } catch (\Exception $e) {
        // Log other errors
        Log::error("User creation failed: " . $e->getMessage());
        toastr()->error("User creation failed: " . $e->getMessage());
        return back()->withInput();
    }
    }




    // Get staff info by staff ID
    public function getStaffInfo(Request $request)
    {
        $user = User::with('department', 'roles')->where('staff_id', $request->staff_id)->first();
        if (!$user) return response()->json(['error' => 'Invalid Staff ID'], 404);

        return response()->json([
            'name' => $user->name,
            'department' => $user->department->name ?? '',
            'role' => $user->roles->pluck('name')->first() ?? '',
            'user_id' => $user->id,
        ]);
    }

    public function downloadIdCard($id)
    {
        $staff = User::findOrFail($id);
        $pdf = Pdf::loadView('admin.users.id-card', compact('staff'))->setPaper('a7')->setWarnings(false);
        return $pdf->download("IDCard-{$staff->staff_id}.pdf");
    }

    public function previewStaffID($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.preview-staff-id', compact('user'));
    }

    // Edit user form
    public function editUser($id)
    {
        $user = User::with('roles', 'department')->findOrFail($id);
        $roles = Role::all()->pluck('name', 'id');
        $departments = Department::all();

        $subRoles = SubRole::all()
            ->groupBy('department_id')
            ->map(function ($subs) {
                return $subs->pluck('title', 'id')->mapWithKeys(fn($title, $id) => [(int)$id => $title]);
            })
            ->mapWithKeys(fn($subs, $deptId) => [(int)$deptId => $subs]);

        return view('components.modals.edit-user', [
            'user' => $user,
            'role' => $roles,
            'departments' => $departments,
            'subRoles' => $subRoles,
            'leaveTypes' => LeaveType::where('is_excluded', false)->get(),
            'selectedRoles' => $user->roles->pluck('name')->toArray(),
            'selectedSubRole' => $user->sub_role_id,
            'selectedDepartment' => $user->department_id,
        ]);
    }

    // Update user
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'corporate_email' => 'nullable|email|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'gender'=> 'required|in:male,female,other',
                'user_type' => 'required|in:employee,national_service,intern',
                'department_id' => 'required|exists:departments,id',
                'role' => 'nullable|string|in:super_admin,admin,hr,manager,finance,supervisor,staff',
                'sub_role_id' => 'nullable|integer|exists:sub_roles,id',
                'leave_type_id' => 'nullable|exists:leave_types,id',
                'leave_days' => 'nullable|integer|min:0',
                'kin_name' => 'nullable|string|max:255',
                'kin_relationship' => 'nullable|string|max:50|in:parent,child,spouse,sibling,friend,other',
                'kin_relationship_other' => 'nullable|string|max:50|required_if:kin_relationship,other',
                'kin_phone' => 'nullable|string|max:20',
                'kin_email' => 'nullable|email|max:255',
                'kin_address' => 'nullable|string|max:255',
                'kin_date_of_birth' => 'nullable|date',
                'emergency_name' => 'nullable|string|max:255',
                'emergency_relationship' => 'nullable|string|max:50|in:parent,spouse,sibling,friend,other',
                'emergency_relationship_other' => 'nullable|string|max:50|required_if:emergency_relationship,other',
                'emergency_phone' => 'nullable|string|max:20',
                'emergency_email' => 'nullable|email|max:255',
                'emergency_address' => 'nullable|string|max:255',
                'emergency_age' => 'nullable|integer|min:18',
                'employment_type' => 'required|string|in:fulltime,parttime,contract',
                'date_of_joining' => 'nullable|date',
                'work_location' => 'nullable|string|max:255',
                'salary' => 'nullable|numeric|min:0',
                'benefits' => 'nullable|string|max:500',
                'contract_duration' => 'nullable|string|max:100',
            ]);
        } catch (ValidationException $e) {
            foreach ($e->validator->errors()->all() as $message) {
                toastr()->error($message);
            }
            return back()->withErrors($e->errors())->withInput();
        }

        DB::beginTransaction();
        try {
            $data = $request->all();

            if ($request->hasFile('face_image')) {
                if ($user->face_image && file_exists(public_path($user->face_image))) unlink(public_path($user->face_image));
                $fileName = 'face_' . uniqid() . '.' . $request->file('face_image')->getClientOriginalExtension();
                $request->file('face_image')->move(public_path('face_images'), $fileName);
                $data['face_image'] = 'face_images/' . $fileName;
            }
            if ($request->hasFile('avatar')) {
                if ($user->avatar) Storage::disk('public')->delete($user->avatar);
                $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }
            if ($request->filled('password')) $data['password'] = bcrypt($request->password);
            else unset($data['password']);

            $user->update($data);
            if ($request->filled('role')) $user->syncRoles([$request->role]);
            if ($request->filled('leave_type_id') && $request->filled('leave_days')) {
                LeaveBalance::updateOrCreate(
                    ['user_id' => $user->id, 'leave_type_id' => $request->leave_type_id],
                    ['allocated_days' => $request->leave_days]
                );
            }

            DB::commit();
            toastr()->success('User updated successfully');
            return redirect()->route('admin.users.index');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update user: " . $e->getMessage());
            toastr()->error('User update failed. Please try again.');
            return back()->withInput();
        }
    }

    // Delete user
    public function destroyUser($id)
    {
        $user = User::with('subordinates')->findOrFail($id);

        if ($user->subordinates()->count() > 0) {
            toastr()->error('Cannot delete this user because they have assigned subordinates.');
            return redirect()->back();
        }

        \DB::transaction(function() use ($user) {
            \DB::table('sessions')->where('user_id', $user->id)->delete();
            $user->syncRoles([]);
            $user->syncPermissions([]);
            $user->delete();
        });

        toastr()->success('User deleted successfully.');
        return redirect()->route('all.staffs');
    }

    // User performance metrics
    public function getUserPerformance($id)
    {
        $user = User::with('tasks')->findOrFail($id);
        $completed = $user->tasks->where('status', 'completed')->count();
        $overdue = $user->tasks->where('due_date', '<', now())->where('status', '!=', 'completed')->count();

        return response()->json([
            'completed_tasks' => $completed,
            'overdue_tasks' => $overdue,
        ]);
    }

    public function messages()
    {
        return [
            'name.required' => 'Full name is required.',
            'email.required' => 'Corporate email is required.',
            'gender.in' => 'Please select a valid gender.',
            'user_type.in' => 'Invalid user type selected.',
            'phone.max' => 'Phone number cannot exceed 10 characters.',
        ];
    }
}
