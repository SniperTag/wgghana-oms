<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\LeaveService;
use App\Notifications\LeaveStatusNotification;


class LeaveController extends Controller
{
    use AuthorizesRequests;

    protected $leaveService;

    /**
     * Inject LeaveService dependency.
     */
    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    /**
     * Display authenticated user's leave requests and statistics.
     */
    public function index()
    {
        $user = Auth::user();

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

        if ($annualLeaveType) {
            $leaveBalance = LeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $annualLeaveType->id)
                ->first();
        }

        return view('leaves.index', compact(
            'leaves',
            'user',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'onLeaveCount',
            'annualLeaveType',
            'leaveBalance'
        ));
    }

    /**
     * Show the form to create a new leave request.
     */
    public function create()
    {
        $user = Auth::user();
        $leaveTypes = LeaveType::all();

        $annualLeaveType = LeaveType::where('name', 'Annual Leave')->first();
        $leaveBalance = null;
        $totalAnnualLeave = 0;
        $remainingAnnualLeave = 0;

        if ($annualLeaveType) {
            $leaveBalance = LeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $annualLeaveType->id)
                ->first();

            if ($leaveBalance) {
                $totalAnnualLeave = $leaveBalance->total_days;
                $remainingAnnualLeave = $leaveBalance->remaining_days;
            }
        }

        $leaves = Leave::with(['user', 'leaveType', 'approvedByUser'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('leaves.create', compact(
            'user',
            'leaveTypes',
            'leaveBalance',
            'totalAnnualLeave',
            'remainingAnnualLeave',
            'leaves',
            'annualLeaveType'
        ));
    }

    /**
     * Store a new leave request.
     */
    public function store(Request $request)
    {
        try {
            Log::info("LeaveController@store called by user: " . Auth::id());

            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'leave_type_id' => 'required|exists:leave_types,id',
                'reason' => 'required|string|max:1000',
            ]);

            $user = Auth::user();

            $daysRequested = $this->leaveService->calculateWorkingDays(
                $request->start_date,
                $request->end_date
            );

            $balance = LeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $request->leave_type_id)
                ->lockForUpdate()
                ->first();

            if (!$balance) {
                toastr()->error('Leave balance record not found.');
                return redirect()->back();
            }

            if ($balance->remaining_days < $daysRequested) {
                toastr()->error('Insufficient leave balance.');
                return redirect()->back();
            }

            // Supervisor assignment
            $supervisorId = $user->supervisor_id;
            $isSupervisor = User::where('supervisor_id', $user->id)->exists();

            if ($isSupervisor && !$supervisorId) {
                $supervisorRequired = false;
                $supervisorStatus = 'Not Required';
                $supervisorId = null;
            } else {
                $supervisorRequired = true;
                $supervisorStatus = 'Pending';
            }

            $leave = Leave::create([
                'user_id' => $user->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'leave_type_id' => $request->leave_type_id,
                'reason' => $request->reason,
                'days_requested' => $daysRequested,
                'status' => 'Pending',
                'supervisor_status' => $supervisorStatus,
                'supervisor_id' => $supervisorId,
                'supervisor_required' => $supervisorRequired,
                'supervisor_approved_at' => null,
                'approved_by' => null,
                'approved_at' => null,
            ]);

            $balance->increment('used_days', $daysRequested);
            $balance->decrement('remaining_days', $daysRequested);

            $leave->addLog('created', $user->id, 'Leave request submitted.');

            Log::info("Leave ID {$leave->id} submitted by user: {$user->id}");
            toastr()->success('Leave request submitted successfully.');
            return redirect()->route('leaves.index');
        } catch (\Exception $e) {
            Log::error("Error in LeaveController@store", [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            toastr()->error('Failed to submit leave request.');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing a leave request.
     */
    public function edit(Leave $leave)
    {
        $this->authorize('update', $leave);

        $leaveTypes = LeaveType::all();

        return view('leaves.edit', compact('leave', 'leaveTypes'));
    }

    /**
     * Update a leave request.
     */
    public function update(Request $request, Leave $leave)
    {
        try {
            Log::info("LeaveController@update called by user: " . Auth::id());

            $this->authorize('update', $leave);

            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'leave_type_id' => 'required|exists:leave_types,id',
                'reason' => 'required|string|max:1000',
            ]);

            $daysRequested = $this->leaveService->calculateWorkingDays(
                $request->start_date,
                $request->end_date
            );

            if ($leave->status === 'pending') {
                $balance = LeaveBalance::where('user_id', $leave->user_id)
                    ->where('leave_type_id', $request->leave_type_id)
                    ->lockForUpdate()
                    ->first();

                if (!$balance || $balance->remaining_days < $daysRequested) {
                    toastr()->error('Insufficient leave balance for update.');
                    return redirect()->back();
                }
            }

            $leave->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'leave_type_id' => $request->leave_type_id,
                'reason' => $request->reason,
                'days_requested' => $daysRequested,
            ]);

            $leave->addLog('updated', Auth::id(), 'Leave request updated.');
            toastr()->success('Leave updated successfully.');
            return redirect()->route('leaves.index');
        } catch (\Exception $e) {
            Log::error("Error in LeaveController@update: " . $e->getMessage());
            toastr()->error('Failed to update leave request.');
            return redirect()->back();
        }
    }

    /**
     * Approve a leave request based on role (Supervisor or HR/Admin).
     */
    public function approve(Leave $leave)
{
    try {
        $user = Auth::user();

        if ($user->id === $leave->supervisor_id && $leave->supervisor_status === 'Pending') {
            $leave->supervisor_status = 'Approved';
            $leave->supervisor_approved_at = now();
            $leave->addLog('supervisor_approved', $user->id, 'Approved by supervisor.');
            $leave->save();

            toastr()->success('Leave approved by supervisor.');
            return redirect()->back();
        }

        if ($user->hasAnyRole(['HR', 'Admin', 'SuperAdmin'])) {
            if ($leave->supervisor_required && $leave->supervisor_status !== 'Approved') {
                toastr()->error('Supervisor approval is required first.');
                return redirect()->back();
            }

            // Fully approve leave
            $leave->status = 'approved';
            $leave->approved_by = $user->id;
            $leave->approved_at = now();
            $leave->addLog('approved', $user->id, 'Approved by HR/Admin.');
            $leave->save();

            // Send notification to user about approval
            $leave->user->notify(new LeaveStatusNotification(
                "Your leave request from {$leave->start_date} to {$leave->end_date} has been approved.",
                route('staff.leaves.show', $leave->id)
            ));

            toastr()->success('Leave approved successfully.');
            return redirect()->back();
        }

        toastr()->error('Unauthorized to approve leave.');
        return redirect()->back();
    } catch (\Exception $e) {
        Log::error("Error in LeaveController@approve: " . $e->getMessage());
        toastr()->error('Approval failed.');
        return redirect()->back();
    }
}

public function reject(Leave $leave)
{
    try {
        $user = Auth::user();

        // Supervisor rejecting leave when pending supervisor approval
        if ($user->id === $leave->supervisor_id && $leave->supervisor_status === 'Pending') {
            $leave->supervisor_status = 'Rejected';
            $leave->supervisor_rejected_at = now();
            $leave->addLog('supervisor_rejected', $user->id, 'Rejected by supervisor.');
            $leave->save();

            // Notify user
            $leave->user->notify(new LeaveStatusNotification(
                "Your leave request from {$leave->start_date} to {$leave->end_date} has been rejected by your supervisor.",
                route('staff.leaves.show', $leave->id)
            ));

            toastr()->success('Leave rejected by supervisor.');
            return redirect()->back();
        }

        // HR/Admin rejecting leave (only if supervisor approved if required)
        if ($user->hasAnyRole(['HR', 'Admin', 'SuperAdmin'])) {
            if ($leave->supervisor_required && $leave->supervisor_status !== 'Approved') {
                toastr()->error('Supervisor approval is required first.');
                return redirect()->back();
            }

            $leave->status = 'rejected';
            $leave->rejected_by = $user->id;
            $leave->rejected_at = now();
            $leave->addLog('rejected', $user->id, 'Rejected by HR/Admin.');
            $leave->save();

            // Notify user
            $leave->user->notify(new LeaveStatusNotification(
                "Your leave request from {$leave->start_date} to {$leave->end_date} has been rejected by HR/Admin.",
                route('staff.leaves.show', $leave->id)
            ));

            toastr()->success('Leave rejected successfully.');
            return redirect()->back();
        }

        toastr()->error('Unauthorized to reject leave.');
        return redirect()->back();
    } catch (\Exception $e) {
        Log::error("Error in LeaveController@reject: " . $e->getMessage());
        toastr()->error('Rejection failed.');
        return redirect()->back();
    }
}

    /**
     * List supervisor's pending leave requests.
     */
    public function supervisorPending()
    {
        $user = Auth::user();

        $leaves = Leave::where('supervisor_id', $user->id)
            ->where('supervisor_status', 'Pending')
            ->latest()
            ->paginate(10);

        return view('admin.leaves.supervisor_pending', compact('leaves'));
    }

    /**
     * List leaves pending HR/Admin final approval.
     */
    public function hrPending()
    {
        $leaves = Leave::where('status', 'Pending')
            ->where(function ($query) {
                $query->where('supervisor_required', false)
                    ->orWhere('supervisor_status', 'Approved');
            })
            ->latest()
            ->paginate(10);

        return view('admin.leaves.hr_pending', compact('leaves'));
    }
}
