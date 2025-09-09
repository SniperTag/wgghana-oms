<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\AttendanceRecord;
use App\Models\User;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\UserDataService;

class SupervisorController extends Controller
{
   public function dashboard()
{
    $user = Auth::user();

    // Pending approvals for subordinates
    $pendingApprovals = Leave::with(['user', 'leaveType'])
        ->where('supervisor_id', $user->id)
        ->where('supervisor_status', 'pending')
        ->latest()
        ->get();
    $pendingApprovalsCount = $pendingApprovals->count();

    // Team leave stats
    $approvedCount = Leave::where('supervisor_id', $user->id)
        ->where('supervisor_status', 'approved')
        ->count();
    $rejectedCount = Leave::where('supervisor_id', $user->id)
        ->where('supervisor_status', 'rejected')
        ->count();
    $subordinatesOnLeaveCount = Leave::currentlyOnLeave()
        ->where('supervisor_id', $user->id)
        ->count();

    // Attendance stats
    $teamAttendanceToday = AttendanceRecord::whereIn('user_id', $user->subordinates->pluck('id'))
        ->whereDate('attendance_date', now())
        ->count();
    $teamLateCount = AttendanceRecord::whereIn('user_id', $user->subordinates->pluck('id'))
        ->whereMonth('attendance_date', now()->month)
        ->where('status', 'late')
        ->count();

    // Notifications & recent activities
    $notifications = $user->notifications()->latest()->take(5)->get();
    $activities = Leave::with('user')
        ->where('supervisor_id', $user->id)
        ->latest()
        ->take(10)
        ->get();

    return view('supervisor.dashboard', compact(
        'user',
        'pendingApprovalsCount',
        'subordinatesOnLeaveCount',
        'approvedCount',
        'rejectedCount',
        'teamAttendanceToday',
        'teamLateCount',
        'notifications',
        'activities',
        'pendingApprovals'
    ));
}


    // Supervisor profile
    public function profile()
    {
        return view('supervisor.profile');
    }

    // Show leave creation form and user leave stats
    public function create()
    {
        $user = Auth::user();
        $leaveTypes = LeaveType::all();

        // Fetch user's leaves (paginated)
        $leaves = Leave::with(['user', 'leaveType', 'approvedByUser'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        // Aggregate leave status counts
        $leaveCounts = Leave::where('user_id', $user->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $pendingCount = $leaveCounts->get('pending', 0);
        $approvedCount = $leaveCounts->get('approved', 0);
        $rejectedCount = $leaveCounts->get('rejected', 0);

        $onLeaveCount = Leave::currentlyOnLeave()
            ->where('user_id', $user->id)
            ->count();

        // Annual leave balance
        $annualLeaveType = LeaveType::where('name', 'Annual Leave')->first();
        $leaveBalance = null;
        $totalAnnualLeaveCount = 0;

        if ($annualLeaveType) {
            $leaveBalance = LeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $annualLeaveType->id)
                ->first();
            $totalAnnualLeaveCount = $leaveBalance?->total_days ?? 0;
        }

        return view('supervisor.leaves.create', compact(
            'leaves',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'onLeaveCount',
            'leaveBalance',
            'totalAnnualLeaveCount',
            'leaveTypes',
            'annualLeaveType',
            'user'
        ));
    }

    // Store supervisor leave request
    public function storeLeave(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $leave = Leave::create([
                'user_id' => Auth::id(),
                'leave_type_id' => $request->leave_type_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            $leave->addLog('created', Auth::id(), 'Leave request created.');

            DB::commit();
            toastr()->success('Leave request submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Leave request error: " . $e->getMessage());
            toastr()->error('Failed to submit leave request.');
        }

        return redirect()->route('supervisor.leaves.index');
    }

    // View supervisor attendance
    public function attendance(Request $request)
    {
        $user = Auth::user();
        $query = AttendanceRecord::with('user.department')
            ->where('user_id', $user->id);

        // Predefined filters
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

        // Custom date range
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('attendance_date', [$request->from, $request->to]);
        }

        $attendanceRecords = $query->orderBy('attendance_date', 'desc')->paginate(10);

        return view('supervisor.attendance', compact('attendanceRecords', 'user'));
    }

    // Supervisor leave index with optional filters
    public function index(Request $request, UserDataService $userDataService)
    {
        $user = Auth::user();
        $filter = $request->input('filter');
        $from = $request->input('from');
        $to = $request->input('to');
        $hasFilter = $filter || ($from && $to);

        $data = $userDataService->getLeaveAndAttendanceData($user, $filter, $from, $to);

        return view('supervisor.leaves.index', array_merge($data, compact('hasFilter')));
    }

    // Show a single leave request
    public function show($id)
    {
        $leave = Leave::findOrFail($id);
        return view('supervisor.leaves.show', compact('leave'));
    }

    // Subordinates leave index
    public function subordinatesIndex()
    {
        $leaves = Leave::with(['user', 'leaveType'])
            ->where('supervisor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('supervisor.subordinates.index', compact('leaves'));
    }

    // Show subordinate leave request
    public function subordinatesShow($id)
    {
        $leave = Leave::with(['user', 'leaveType'])
            ->where('id', $id)
            ->where('supervisor_id', Auth::id())
            ->firstOrFail();

        return view('supervisor.subordinates.show', compact('leave'));
    }

    // Approve subordinate leave
   public function approve($id)
{
    try {
        DB::beginTransaction();

        $leave = Leave::where('id', $id)
            ->where('supervisor_id', Auth::id())
            ->where('supervisor_status', 'pending')
            ->firstOrFail();

        // Update only supervisor's action
        $leave->update([
            'supervisor_status' => 'approved',        // Supervisor approved
            'supervisor_approved_at' => now(),       // Record timestamp
        ]);

        // Log the supervisor action
        $leave->addLog('approved_by_supervisor', Auth::id(), 'Supervisor approved the leave request.');

        DB::commit();
        toastr()->success('Leave request approved successfully.');

        return redirect()->route('supervisor.subordinates.index');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Supervisor approve error: " . $e->getMessage());
        toastr()->error('Failed to approve request.');
        return redirect()->back();
    }
}


    // Reject subordinate leave
    public function reject(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $leave = Leave::where('id', $id)
                ->where('supervisor_id', Auth::id())
                ->where('supervisor_status', 'pending')
                ->firstOrFail();

            $leave->update([
                'supervisor_status' => 'rejected',
                'supervisor_comment' => $request->input('comment'),
                'rejected_at' => now(),
            ]);

            // Refund leave days if already deducted
            $balance = LeaveBalance::where('user_id', $leave->user_id)
                ->where('leave_type_id', $leave->leave_type_id)
                ->first();

            if ($balance) {
                $balance->used_days -= $leave->days_requested;
                $balance->remaining_days += $leave->days_requested;
                $balance->save();
            }

            $leave->addLog('rejected_by_supervisor', Auth::id(), 'Supervisor rejected the leave request.');

            DB::commit();
            toastr()->success('Leave request rejected.');
            return redirect()->route('supervisor.subordinates.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Supervisor reject error: " . $e->getMessage());
            toastr()->error('Failed to reject request.');
            return redirect()->back();
        }
    }

    // Supervisor breaktime view
    public function breaktime()
    {
        return view('supervisor.breaktime');
    }

    // View pending approvals
    public function pendingApprovals()
    {
        $leaves = Leave::where('supervisor_id', Auth::id())
            ->where('supervisor_status', 'pending')
            ->with(['user', 'leaveType'])
            ->latest()
            ->paginate(10);

        return view('supervisor.leaves.pending', compact('leaves'));
    }
}
