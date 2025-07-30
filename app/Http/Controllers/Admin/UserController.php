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
use Illuminate\Support\Facades\Hash;



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
            $staffId = 'WG-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT) . '-' . date('Y');
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
        $femaleCount = User::where('gender','female')->count();
        $maleCount = User::where('gender', 'male')->count();
        $staffCount = User::where('user_type', 'staff')->count();
        $nationalServiceCount = User::where('user_type', 'national_service')->count();
        // Returning view with necessary data for creating a user
        return view('admin.users.create', compact('roles', 
        'userCount', 'departments',
         'user', 'leaveTypes',
        'femaleCount','maleCount',
    'staffCount', 'nationalServiceCount'));
    }

    // Function to store a new user based on the input from the creation form

    public function storeUser(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'gender'=> 'required|in:male,female,other',
             'user_type' => 'required|in:employee,national_service',
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
            'name', 'email','gender','user_type', 'phone', 'department_id', 'supervisor_id', 'is_active', 'roles'
        ]);
        $data['staff_id'] = $this->generateStaffId();

        if ($request->hasFile('face_image')) {
    $fileName = 'face_' . uniqid() . '.' . $request->file('face_image')->getClientOriginalExtension();
    $request->file('face_image')->move(public_path('face_images'), $fileName);
    $data['face_image'] = 'face_images/' . $fileName;
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
    $user = User::findOrFail($id);
    $user->delete();

    toastr()->success('User deleted successfully');
    return redirect()->route('admin.users.index');
}

// Show registration form for invited user using invite token
public function showRegistrationForm($token)
{
    // Use the correct column name for token expiration - your code uses both invite_token_expiry and invite_expires_at - be consistent!
    $user = User::where('invite_token', $token)->first();

    if (!$user || $user->invite_expires_at < now()) {
        toastr()->error('Invalid or expired invite token');
        return redirect()->route('login'); // Better to redirect to login or home, not admin.users_index
    }

    return view('user.invite-register', compact('token', 'user'));
}

// Register the invited user with form data
public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'password' => 'required|string|min:6|confirmed',
        'token' => 'required|string',
    ]);

    $user = User::where('invite_token', $request->token)->first();

    if (!$user || $user->invite_expires_at < now()) {
        toastr()->error('Invalid or expired invite token');
        return redirect()->route('login');
    }

    // Update invited user with name, password, activate, clear invite token
    $user->update([
        'name' => $request->name,
        'password' => bcrypt($request->password),
        'is_active' => true,
        'is_invited' => false,
        'invite_token' => null,
        'invite_expires_at' => null,
        'invite_token_used_at' => now(),
    ]);

    // Optionally log the user in immediately
    Auth::login($user);

    Log::info("User registered: {$user->email}");

    return redirect()->route('pin.change.form'); // or wherever next step is
}

// Show invite user form to admin with roles to assign
public function invite()
{
    $roles = Role::all();
    $userCount = User::count();
    $user = Auth::user();

    return view('admin.users.invite', compact('userCount', 'user', 'roles'));
}

// Store the invite, create user, assign roles, send invite email
public function inviteStore(InviteStoreRequest $request)
{
    $request->validate([
        'email' => 'required|email|unique:users,email',
        'roles' => 'required|array',
        'roles.*' => 'exists:roles,name',
    ]);

    try {
        // Create invited user with email and roles
        $user = InviteService::createInvitedUser($request->email, $request->roles);

        // Send invite email with token etc.
        InviteService::sendInvite($user);

        Log::info("Invitation sent to: {$user->email}");
        toastr()->success('Invite sent successfully');
        return redirect()->back();
    } catch (\Exception $e) {
        Log::error("Failed to send invite: " . $e->getMessage());
        toastr()->error('Failed to send invite. ' . $e->getMessage());
        return back()->withInput();
    }
}
 public function processRegistration(Request $request)
    {
        $request->validate([
            'token' => 'required|exists:users,invite_token',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('invite_token', $request->token)
                    ->where('invite_expires_at', '>', now())
                    ->where('is_invited', true)
                    ->first();

        if (!$user) {
            return back()->withErrors(['token' => 'Invalid or expired invite token.']);
        }

        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->is_active = true;
        $user->is_invited = false;
        $user->invite_token = null;
        $user->invite_expires_at = null;
        $user->save();

        Log::info("User {$user->email} completed invite registration.");

        return redirect()->route('login')->with('success', 'Registration complete! You can now log in.');
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
