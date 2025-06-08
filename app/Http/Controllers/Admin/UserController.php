<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use App\Services\InviteService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\InviteStoreRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\LeavePolicy;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserWelcomeMail;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\Password;


class UserController extends Controller
{

    // Constant for the default clock-in PIN

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
        DB::beginTransaction();

        try {
            Log::info('Storing user started');

            $randomPassword = Str::random(8);
            $randomClockinPin = rand(1000, 9999);

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->department_id = $request->department_id;
            $user->supervisor_id = $request->supervisor_id;
            $user->is_active = $request->is_active ? 1 : 0;
            $user->staff_id = $this->generateStaffId();
            $user->clockin_pin = Hash::make($randomClockinPin);
            $user->pin_changed = false;
            $user->password_changed = false;
            $user->password = Hash::make($request->password);
            $user->save();

            // Generate password reset token and URL
            $token = Password::createToken($user);
            $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);
            Log::info("Password reset URL generated: {$resetUrl}");

            // Send welcome email with reset link and PIN
            Mail::to($user->email)->send(new UserWelcomeMail($user, $randomPassword, $resetUrl, $randomClockinPin));
            Log::info("Welcome email sent to: {$user->email}");

            Log::info('User created: ', ['id' => $user->id]);

            DB::commit();
            toastr()->success('User created and welcome email sent!');
            return redirect()->route('admin.users_index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            toastr()->error('Failed to create user.');
            return back()->withInput();
        }
    }


    // Function to generate a unique staff ID for a new user
    public function generateStaffId()
    {
        $lastUser = User::orderBy('id', 'desc')->first();
        $nextId = $lastUser ? $lastUser->id + 1 : 1;
        return 'WG-' . str_pad($nextId, 4, '0', STR_PAD_LEFT) . '-' . date('Y');
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

    // Function to show the user edit form with current user details, roles, and departments
    public function editUser($id)
    {
        // Fetching user by ID, along with roles and departments
        $user = User::findOrFail($id);
        $roles = Role::all();
        $departments = Department::all();
        // Returning the edit view with user data
        return view('components.modals.edit-user', [
            'user' => $user,
            'roles' => $roles,
            'departments' => $departments,
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
