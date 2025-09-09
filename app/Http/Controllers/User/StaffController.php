<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Leave;
use App\Models\BreakTime;
use App\Models\LeaveType;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeavePolicy;
use Illuminate\Support\Str;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

use Illuminate\Support\Facades\Validator;
use Illuminate\Notifications\Notification;


class StaffController extends Controller
{
    // Function to show the staff dashboard


   public function staff()
{
    $user = Auth::user();

    // Supervisor info
    $supervisor = User::find($user->supervisor_id);

    // Leave records and counts
    $leaves = Leave::with('leaveType', 'approver')
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    $pendingCount = Leave::where('user_id', $user->id)->where('status', 'pending')->count();
    $approvedCount = Leave::where('user_id', $user->id)->where('status', 'approved')->count();
    $rejectedCount = Leave::where('user_id', $user->id)->where('status', 'rejected')->count();
    $onLeaveCount = Leave::currentlyOnLeave()->where('user_id', $user->id)->count();

    // Annual Leave Balance
    $annualLeaveType = LeaveType::where('name', 'Annual Leave')->first();
    $leaveBalance = $annualLeaveType
        ? LeaveBalance::where('user_id', $user->id)->where('leave_type_id', $annualLeaveType->id)->first()
        : null;

    $totalAnnualLeaveCount = $leaveBalance?->total_days ?? 0;
    $remainingLeaveDays = $leaveBalance?->remaining_days ?? 0;

    // Monthly Leaves for last 6 months (trend chart)
    $monthlyLeaves = Leave::select(
            DB::raw("DATE_FORMAT(start_date, '%b %Y') as month"),
            DB::raw("COUNT(*) as total")
        )
        ->where('user_id', $user->id)
        ->where('start_date', '>=', now()->subMonths(6)->startOfMonth())
        ->groupBy('month')
        ->orderByRaw("MIN(start_date)")
        ->pluck('total', 'month');

    $trendLabels = $monthlyLeaves->keys()->toArray(); // e.g. ['Jan 2025', 'Feb 2025', ...]
    $trendData = $monthlyLeaves->values()->toArray(); // e.g. [3, 1, 4, ...]

    // Leave Type Breakdown (doughnut chart)
    $leaveTypeData = Leave::select(
            'leave_type_id',
            DB::raw('count(*) as total')
        )
        ->where('user_id', $user->id)
        ->groupBy('leave_type_id')
        ->get();

    // Map leave type names and counts
    $typeLabels = [];
    $typeData = [];

    foreach ($leaveTypeData as $data) {
        $type = LeaveType::find($data->leave_type_id);
        if ($type) {
            $typeLabels[] = $type->name;
            $typeData[] = $data->total;
        }
    }

    // Notifications (latest 5)
    $notifications = $user->notifications()->latest()->limit(5)->get();
   $attendanceStatus = $user->attendanceRecords()
    ->whereDate('attendance_date', now())
    ->value('status') ?? 'Absent';

// Count of late arrivals this month (integer)
$lateCount = $user->attendanceRecords()
    ->whereMonth('attendance_date', now()->month)
    ->where('status', 'Late')
    ->count();

$activities = Activity::causedBy($user)
    ->latest()
    ->limit(5)
    ->get();
    return view('staff.dashboard', compact(
        'user',
        'supervisor',
        'leaves',
        'pendingCount',
        'approvedCount',
        'rejectedCount',
        'onLeaveCount',
        'totalAnnualLeaveCount',
        'remainingLeaveDays',
        'trendLabels',
        'trendData',
        'typeLabels',
        'typeData',
        'notifications',
'attendanceStatus',
'lateCount',
'activities'
    ));
}



    // Function to show the staff profile
    public function profile()
    {
        // Fetching the authenticated user
        $user = Auth::user();
        // Returning the view with the user data
        return view('staff.profile', compact('user'));
    }

    // Function to show the staff attendance page
     public function attendance(Request $request)
{
    $user = Auth::user();

    $query = AttendanceRecord::with('user.department')
        ->where('user_id', $user->id);

    // Handle predefined filters
    if ($request->filled('filter')) {
        switch ($request->filter) {
            case 'today':
                $query->whereDate('attendance_date', now());
                break;
            case 'this_week':
                $query->whereBetween('attendance_date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereBetween('attendance_date', [now()->startOfMonth(), now()->endOfMonth()]);
                break;
        }
    }

    // Handle custom date range
    if ($request->filled('from') && $request->filled('to')) {
        $query->whereBetween('attendance_date', [$request->from, $request->to]);
    }

    $attendanceRecords = $query->orderBy('attendance_date', 'desc')->paginate(10);

    return view('staff.attendance', compact('attendanceRecords', 'user'));
}


    //Function to create and View Leave
    public function apply()
    {
        $user = Auth::user();

        // Get all leaves for this user
        $leaves = Leave::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get all leave types
        $leaveTypes = LeaveType::all();

        // Leave request status counts
        $pendingCount = Leave::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $approvedCount = Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();

        $rejectedCount = Leave::where('user_id', $user->id)
            ->where('status', 'rejected')
            ->count();

        // Count if user is currently on leave
        $onLeaveCount = Leave::currentlyOnLeave()
            ->where('user_id', $user->id)
            ->count('user_id');

        // Initialize leave balance and total annual leave count
        $leaveBalance = null;
        $totalAnnualLeaveCount = 0;

        // Get Annual Leave type
        $annualLeaveType = LeaveType::where('name', 'Annual Leave')->first();

        if ($annualLeaveType) {
            $leaveBalance = LeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $annualLeaveType->id)
                ->first();

            // If leave balance exists, get total annual leave days allocated
            $totalAnnualLeaveCount = $leaveBalance?->total_days ?? 0;
        }

        // Return the view with all required data
        return view('staff.leaves.apply', compact(
            'leaves',
            'leaveTypes',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'onLeaveCount',
            'leaveBalance',
            'totalAnnualLeaveCount',
            'annualLeaveType',
        ));
    }


    // Function to show the staff leave index page
public function index(Request $request)
{
    $user = Auth::user();

    // -------------------------------
    // 1️⃣ User's leaves with eager loading & filters
    // -------------------------------
    $query = Leave::with('leaveType', 'approver')
                  ->where('user_id', $user->id);

    if ($request->filled('from')) {
        $query->whereDate('start_date', '>=', $request->input('from'));
    }

    if ($request->filled('to')) {
        $query->whereDate('end_date', '<=', $request->input('to'));
    }

    if ($request->filled('status')) {
        $query->where('status', $request->input('status'));
    }

    if ($request->filled('search')) {
        $searchTerm = $request->input('search');
        $query->whereHas('leaveType', function ($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%");
        });
    }

    $leaves = $query->orderBy('created_at', 'desc')->get();

    // -------------------------------
    // 2️⃣ Leave request status counts
    // -------------------------------
    $pendingCount  = Leave::where('user_id', $user->id)->where('status', 'pending')->count();
    $approvedCount = Leave::where('user_id', $user->id)->where('status', 'approved')->count();
    $rejectedCount = Leave::where('user_id', $user->id)->where('status', 'rejected')->count();
    $onLeaveCount  = Leave::currentlyOnLeave()->where('user_id', $user->id)->count();

    // -------------------------------
    // 3️⃣ Annual Leave balance
    // -------------------------------
    $annualLeaveType = LeaveType::where('name', 'Annual Leave')->first();

    $totalAnnualLeaveCount = 0;
    $usedDays = 0;
    $remainingDays = 0;
    $leaveBalance = null;

    if ($annualLeaveType) {
        // Check leave_balances for this user and type
        $leaveBalance = LeaveBalance::where('user_id', $user->id)
            ->where('leave_type_id', $annualLeaveType->id)
            ->first();

        if ($leaveBalance) {
            $totalAnnualLeaveCount = $leaveBalance->total_days;
            $usedDays = $leaveBalance->used_days;
            $remainingDays = $leaveBalance->remaining_days;
        } else {
            // Fallback to LeavePolicy if no balance exists
            $policy = LeavePolicy::where('role_id', $user->role_id)
                                 ->orWhere('department_id', $user->department_id)
                                 ->first();

            $totalAnnualLeaveCount = $policy ? $policy->annual_days : 0;
            $usedDays = 0;
            $remainingDays = $totalAnnualLeaveCount;
        }
    }

    // -------------------------------
    // 4️⃣ Return view with all data
    // -------------------------------
    return view('staff.leaves.index', compact(
        'leaves',
        'pendingCount',
        'approvedCount',
        'rejectedCount',
        'onLeaveCount',
        'leaveBalance',
        'totalAnnualLeaveCount',
        'usedDays',
        'remainingDays'
    ));
}




    // Function to show the staff leave create page
    public function show($id)
    {
        // Fetching the leave record by ID
        $leave = Leave::findOrFail($id);
        // Returning the view with the leave data


        return view('staff.leave.show', compact('leave'));
    }
}
