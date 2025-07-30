<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\UserDataService;

class SupervisorController extends Controller
{
    public function supervisor()
    {
        return view('supervisor.dashboard');
    }

    public function profile()
    {
        return view('supervisor.profile');
    }
    public function create()
    {
        // Fetching the authenticated user
        $user = Auth::user();
        $leaveTypes = LeaveType::all();

        // Fetching the leave records for the authenticated user
        $leaves = Leave::with(['user', 'leaveType', 'approvedByUser'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $pendingCount = Leave::where('user_id', $user->id)->where('status', 'pending')->count();
        $approvedCount = Leave::where('user_id', $user->id)->where('status', 'approved')->count();
        $rejectedCount = Leave::where('user_id', $user->id)->where('status', 'rejected')->count();
        $onLeaveCount = Leave::currentlyOnLeave()->where('user_id', $user->id)->count();

        $annualLeaveType = LeaveType::where('name', 'Annual Leave')->first();
        $leaveBalance = null;
        $totalAnnualLeaveCount = 0;

        if ($annualLeaveType) {
            $leaveBalance = LeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $annualLeaveType->id)
                ->first();
            $totalAnnualLeaveCount = $leaveBalance?->total_days ?? 0;
        }
        $leaves = Leave::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
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

    //Leave Store Function
    public function storesupervisorleave(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $leave = new Leave();
            $leave->user_id = Auth::id();
            $leave->leave_type_id = $request->leave_type_id;
            $leave->start_date = $request->start_date;
            $leave->end_date = $request->end_date;
            $leave->reason = $request->reason;
            $leave->status = 'pending';
            $leave->save();

            // Optional: log the action
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



    // Function to view attendance records for supervisor
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

    return view('supervisor.attendance', compact('attendanceRecords','user'));
}


    public function index(Request $request, UserDataService $userDataService)
    {
         $user = auth::user();

    $filter = $request->input('filter');
    $from = $request->input('from');
    $to = $request->input('to');

    $hasFilter = $filter || ($from && $to); 

    $data = $userDataService->getLeaveAndAttendanceData($user, $filter, $from, $to);

    return view('supervisor.leaves.index', array_merge($data, compact('hasFilter')));
    }

    // Function to show the leave request form
    public function show($id)
    {
        $leave = Leave::findOrFail($id);
        return view('supervisor.leaves.show', compact('leave'));
    }

    // Function to show the leave edit form
    public function subordinatesIndex()
    {
        $supervisorId = Auth::id();
        $leaves = Leave::with(['user', 'leaveType'])
            ->where('supervisor_id', $supervisorId)
            ->latest()
            ->paginate(10);

        return view('supervisor.subordinates.index', compact('leaves'));
    }

    // Function to show the leave request form for subordinates
    public function subordinatesShow($id)
    {
        $leave = Leave::with(['user', 'leaveType'])
            ->where('id', $id)
            ->where('supervisor_id', Auth::id()) // for access control
            ->firstOrFail();

        return view('supervisor.subordinates.show', compact('leave'));
    }


    public function approve($id)
    {
        try {
            DB::beginTransaction();

            $leave = Leave::where('id', $id)
                ->where('supervisor_id', Auth::id())
                ->where('supervisor_status', 'pending')
                ->firstOrFail();

            $leave->supervisor_status = 'approved';
            $leave->supervisor_approved_at = now();
            $leave->save();

            // Optional: log the action
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

    public function reject(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $leave = Leave::where('id', $id)
                ->where('supervisor_id', Auth::id())
                ->where('supervisor_status', 'pending')
                ->firstOrFail();

            $leave->supervisor_status = 'rejected';
            $leave->rejected_at = now();
            $leave->supervisor_comment = $request->input('comment'); // optional comment
            $leave->save();

            // Refund leave days to balance if already deducted
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

    public function breaktime()
    {
        return view('supervisor.breaktime');
    }

    // View all pending leave requests from subordinates
    public function pendingApprovals()
    {
        $supervisorId = Auth::id();

        $leaves = Leave::where('supervisor_id', $supervisorId)
            ->where('supervisor_status', 'Pending')
            ->with(['user', 'leaveType'])
            ->latest()
            ->paginate(10);

        return view('supervisor.leaves.pending', compact('leaves'));
    }
    // Reject leave request
}
