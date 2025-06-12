<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\User;
use App\Models\LeaveType;
use App\Models\Department;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\InviteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\InviteStoreRequest;
use Illuminate\Validation\ValidationException;



class UserController extends Controller
{
    /**
     * Generate a unique staff ID for a new user.
     *
     * @return string
     */
    protected function generateStaffId()
    {
        // Example: Generate a staff ID with prefix 'STAFF' and a unique number
        do {
            $staffId = 'WG-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT) . '-' . date('Y');;
        } while (User::where('staff_id', $staffId)->exists());

        return $staffId;
    }


    // Function to display the list of users with pagination, roles, and departments
    public function indexUser()
    {
        // $user = Auth::get();
        // Fetching users with roles and paginating the result
        $users = User::with(['roles', 'department'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        // Getting the total count of users
        $userCount = User::count();
        // Fetching all roles and departments
        $roles = Role::all();
        $departments = Department::all();
        // Returning view with the users data
        return view('admin.users.index', compact('users', 'userCount', 'roles', 'departments'));
    }

    // Function to show the user creation form with roles, user count, and departments
    public function createUser()
    {
        $user = Auth::user();
        // Fetching roles, user count, and departments for the creation form
        $roles = Role::all();
        $userCount = User::count();
        $departments = Department::all();
        $leaveTypes = LeaveType::where('is_excluded', false)->get();
        // Returning view with necessary data for creating a user
        return view('admin.users.create', compact('roles', 'userCount', 'departments', 'user', 'leaveTypes'));
    }

    // Function to store a new user based on the input from the creation form

    public function storeUser(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'department_id' => 'required|exists:departments,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'face_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'nullable|boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'leave_type_id' => 'nullable|exists:leave_types,id',
            'leave_days' => 'nullable|integer|min:0',
        ]);
    } catch (ValidationException $e) {
        // Show each validation error via Toastr
        foreach ($e->validator->errors()->all() as $message) {
            toastr()->error($message);
        }

        return back()->withErrors($e->errors())->withInput();
    }

    DB::beginTransaction();

    try {
        $data = $request->only([
            'name', 'email', 'phone', 'department_id', 'supervisor_id', 'is_active', 'roles'
        ]);
        $data['staff_id'] = $this->generateStaffId();

        if ($request->hasFile('face_image')) {
            $data['face_image'] = base64_encode(file_get_contents($request->file('face_image')));
        }

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = UserService::createUserWithWelcomeEmail($data);

        if ($request->filled('leave_type_id') && $request->filled('leave_days')) {
            LeaveBalance::create([
                'user_id' => $user->id,
                'leave_type_id' => $request->leave_type_id,
                'allocated_days' => $request->leave_days,
                'used_days' => 0,
            ]);
        }

        DB::commit();
        toastr()->success('User created successfully and welcome email sent!');
        return redirect()->back();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Failed to create user: ' . $e->getMessage());
        toastr()->error('User creation failed. Please try again.');
        return back()->withInput();
    }
}




    // Function to get user details based on the staff ID
    public function getStaffInfo(Request $request)
    {
        // Fetching user by staff ID and including department and roles
        $user = User::with('department', 'roles')->where('staff_id', $request->staff_id)->first();

        // Returning error if user is not found
        if (!$user) {
            return response()->json(['error' => 'Invalid Staff ID'], 404);
        }

        // Returning user data if found
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


    // Function to show the user edit form with current user details, roles, and departments
   public function editUser($id)
{
    $user = User::with('roles', 'department')->findOrFail($id);

    return view('components.modals.edit-user', [
        'user' => $user,
        'roles' => Role::all(),
        'departments' => Department::all(),
        'leaveTypes' => LeaveType::where('is_excluded', false)->get(),
    ]);
}

    // Function to update user details based on the input from the edit form
    public function updateUser(UpdateUserRequest $request, $id)
    {
        // Finding the user to update
        $user = User::findOrFail($id);

        // Preparing data for update
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'phone' => $request->phone,
        ];

        // Updating password if provided
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Updating user data
        $user->update($data);

        // Syncing roles if provided
        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
        }

        // Logging the update action
        Log::info('User updated', ['user_id' => $user->id, 'updated_fields' => $data]);
        // Showing success message to the user
        toastr()->success('User updated successfully');

        // Redirecting back to the user index page
        return redirect()->route('admin.users.index');
    }

    public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
        'department_id' => 'required|exists:departments,id',
        'roles' => 'nullable|array',
        'roles.*' => 'string|exists:roles,name',
    ];
}


    // Function to delete a user based on the provided user ID
    public function destroyUser($id)
    {
        // Finding and deleting the user
        $user = User::findOrFail($id);
        $user->delete();
        // Showing success message
        toastr()->success('User deleted successfully');
        // Redirecting to the user index page
        return redirect()->route('admin.users.index');
    }

    // Function to show the registration form for a user based on the invite token
    public function showRegistrationForm($token)
    {
        // Finding the user with the invite token
        $user = User::where('invite_token', $token)->first();

        // Handling expired or invalid tokens
        if (!$user || $user->invite_token_expiry < now()) {
            toastr()->error('Invalid or expired token');
            return redirect()->route('admin.users_index');
        }

        // Returning the registration view with the token
        return view('auth.register', compact('token'));
    }

    // Function to register the user based on the provided data from the registration form
    public function register(Request $request)
    {
        // Validating registration inputs
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Finding the user with the provided invite token
        $user = User::where('invite_token', $request->token)->first();

        // Handling expired or invalid tokens
        if (!$user || $user->invite_token_expiry < now()) {
            return redirect()->route('admin.users.index')->with('error', 'Invalid or expired token');
        }

        // Updating the user with the registration details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_invited' => true,
            'invite_token' => null,
            'invite_token_expiry' => null,
            'invite_token_used' => now(),
            'department_id' => $request->id,
            'supervisor_id' => $request->supervisor_id,

        ]);

        // Logging the registration event
        Log::info("User registered: {$user->email}");

        // Redirecting to the PIN change form
        return redirect()->route('pin.change.form');
    }


    // Function to show the invite form to send an invite to a new user
    public function invite()
    {
        // Counting the number of users
        $userCount = User::count();
        $user = Auth::user();
        // Returning the invite view with the user count
        return view('admin.users.invite', compact('userCount', 'user'));
    }

    // Function to store the invite by sending it to the provided email
    public function inviteStore(InviteStoreRequest $request)
    {
        // Sending the invite email
        InviteService::sendInvite($request->email);

        // Logging the invite sending event
        Log::info("Invitation sent to: {$request->email}");
        // Showing success message
        toastr()->success('Invite sent successfully');
        // Redirecting to the admin dashboard
        return redirect()->route('admin.dashboard');
    }

    // Function to get the user's performance metrics, such as completed and overdue tasks
    public function getUserPerformance($id)
    {
        // Fetching user along with their tasks
        $user = User::with('tasks')->findOrFail($id);
        // Counting completed and overdue tasks
        $completed = $user->tasks->where('status', 'completed')->count();
        $overdue = $user->tasks->where('due_date', '<', now())->where('status', '!=', 'completed')->count();
        // Returning the task counts as JSON response
        return response()->json([
            'completed_tasks' => $completed,
            'overdue_tasks' => $overdue,
        ]);
    }
}
