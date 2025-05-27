<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Leave;
use App\Models\LeavePolicy;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\AttendanceRecord;
use Illuminate\Notifications\Notification;

use Illuminate\Support\Facades\DB;


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

    return view('staff.staff-dashboard', compact(
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
        'notifications'
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
    public function attendance()
    {
        // Fetching the authenticated user
        $user = Auth::user();
        // Fetching the attendance records for the authenticated user
        $attendanceRecords = AttendanceRecord::where('user_id', $user->id)->orderBy('attendance_date', 'desc')->paginate(10);
        // Returning the view with the attendance records
        return view('staff.attendance', compact('attendanceRecords'));
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

        // Start query for user's leaves with eager loading
        $query = Leave::with('leaveType', 'approver')
            ->where('user_id', $user->id);

        // Apply filters based on request inputs

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

        // Leave request status counts (unfiltered, for overall stats)
        $pendingCount = Leave::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $approvedCount = Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();

        $rejectedCount = Leave::where('user_id', $user->id)
            ->where('status', 'rejected')
            ->count();

        // Count if user is currently on leave (assuming scope currentlyOnLeave() exists)
        $onLeaveCount = Leave::currentlyOnLeave()
            ->where('user_id', $user->id)
            ->count();

        // Leave balance & total annual leave count
        $leaveBalance = null;
        $totalAnnualLeaveCount = 0;

        $annualLeaveType = LeaveType::where('name', 'Annual Leave')->first();

        if ($annualLeaveType) {
            $leaveBalance = LeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $annualLeaveType->id)
                ->first();

            $totalAnnualLeaveCount = $leaveBalance?->total_days ?? 0;
        }

        return view('staff.leaves.index', compact(
            'leaves',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'onLeaveCount',
            'leaveBalance',
            'totalAnnualLeaveCount'
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
