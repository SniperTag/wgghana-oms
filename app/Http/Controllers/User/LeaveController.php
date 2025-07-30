<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Department;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use App\Events\LeaveApproved;
use App\Events\LeaveRejected;
use App\Services\LeaveService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


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
        $today = now()->toDateString();
        $upcomingLeaveCount = Leave::whereDate('start_date', '>', $today)->count();
        $remainingAnnualLeave = $leaveBalance->remaining_days ?? 0;

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
            'leaveBalance',
            'upcomingLeaveCount',
            'remainingAnnualLeave'
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
        $pendingCount = Leave::where('user_id', $user->id)->where('status', 'pending')->count();
        $approvedCount = Leave::where('user_id', $user->id)->where('status', 'approved')->count();
        $rejectedCount = Leave::where('user_id', $user->id)->where('status', 'rejected')->count();
        $totalannualLeaveCount = Leave::where('user_id', $user->id)
            ->whereHas('leaveType', function ($query) {
                $query->where('name', 'Annual Leave');
            })->count();
        $onLeaveCount = Leave::currentlyOnLeave()->where('user_id', $user->id)->count();

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
            'annualLeaveType',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'onLeaveCount'
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
                'status' => 'in:pending,approved,rejected',
            ]);

            $user = Auth::user();
            $leaveType = LeaveType::findOrFail($request->leave_type_id);
            $isSickLeave = strtolower(trim($leaveType->name)) === 'sick leave';

            // ✅ Enforce 3 working-day notice (excluding weekends) unless it's sick leave
            if (!$isSickLeave) {
                $today = now()->startOfDay();
                $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
                $period = \Carbon\CarbonPeriod::create($today->copy()->addDay(), $startDate);
                $workingDays = collect($period)->filter(fn($d) => !$d->isWeekend())->count();

                if ($workingDays < 3) {
                    toastr()->error('You must apply at least 3 working days ahead (excluding weekends).');
                    return redirect()->back();
                }
            }

            // ✅ Overlapping leave check
            $overlap = Leave::where('user_id', $user->id)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('start_date', '<=', $request->start_date)
                                ->where('end_date', '>=', $request->end_date);
                        });
                })
                ->whereIn('status', ['pending', 'approved'])
                ->exists();

            if ($overlap) {
                toastr()->error('You already have a leave request in that period.');
                return redirect()->back();
            }

            $daysRequested = $this->leaveService->calculateWorkingDays(
                $request->start_date,
                $request->end_date
            );

            // ✅ Handle file attachment
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $request->validate([
                    'attachment' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
                ]);
                $attachmentPath = $request->file('attachment')->store('attachments', 'public');
            }

            // ✅ If sick leave but no medical report, fallback to annual leave
            if ($isSickLeave && !$attachmentPath) {
                $annualLeaveType = LeaveType::where('name', 'like', '%annual%')->first();
                if ($annualLeaveType) {
                    toastr()->info('No medical report provided. Deducting from annual leave.');
                    $leaveType = $annualLeaveType;
                }
            }

            // ✅ Fetch leave balance
            $balance = LeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $leaveType->id)
                ->lockForUpdate()
                ->first();

            if (!$balance || $balance->remaining_days < $daysRequested) {
                toastr()->error('Insufficient leave balance.');
                return redirect()->back();
            }

            // ✅ Supervisor logic
            $supervisorRequired = $user->supervisor_id !== null;
            $supervisorId = $supervisorRequired ? $user->supervisor_id : null;
            $supervisorStatus = $supervisorRequired ? 'pending' : 'approved';

            if ($supervisorRequired && !$supervisorId) {
                toastr()->error('No supervisor assigned.');
                return redirect()->back();
            }

            // ✅ Create leave
            $leave = Leave::create([
                'user_id' => $user->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'leave_type_id' => $leaveType->id,
                'reason' => $request->reason,
                'days_requested' => $daysRequested,
                'status' => 'pending',
                'supervisor_status' => $supervisorStatus,
                'supervisor_id' => $supervisorId,
                'supervisor_required' => $supervisorRequired,
                'attachment' => $attachmentPath,
            ]);

            // ✅ Update balance
            $balance->increment('used_days', $daysRequested);
            $balance->decrement('remaining_days', $daysRequested);

            $this->leaveService->logCreation($leave, $user);

            toastr()->success('Leave request submitted successfully.');
            return redirect()->route('leaves.index');
        } catch (\Exception $e) {
            Log::error("Error in LeaveController@store", [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'line' => $e->getLine(),
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

            // Get leave type name
            $leaveType = LeaveType::find($request->leave_type_id);
            if ($leaveType && strtolower($leaveType->name) === 'sick leave') {
                $request->validate([
                    'attachment' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                ]);
            }

            $daysRequested = $this->leaveService->calculateWorkingDays(
                $request->start_date,
                $request->end_date
            );

            if ($leave->status === 'pending') {
                $balance = LeaveBalance::where('user_id', $leave->user_id)
                    ->where('leave_type_id', $request->leave_type_id)
                    ->lockForUpdate()
                    ->first();

                if (!$balance) {
                    toastr()->error('No leave balance record found.');
                    return redirect()->back();
                }

                $difference = $daysRequested - $leave->days_requested;

                if ($difference > 0 && $balance->remaining_days < $difference) {
                    toastr()->error('Insufficient balance for update.');
                    return redirect()->back();
                }

                $balance->used_days += $difference;
                $balance->remaining_days -= $difference;
                $balance->save();
            }

            $leave->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'leave_type_id' => $request->leave_type_id,
                'reason' => $request->reason,
                'days_requested' => $daysRequested,
            ]);

            $this->leaveService->logUpdate($leave, Auth::user());
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
    public function approve(Request $request, $id)
    {
        try {
            $user = Auth::user();
            Log::info("Leave approval attempt by user {$user->id} ({$user->name})");

            $leave = Leave::findOrFail($id);
            Log::info("Leave ID {$leave->id} found for approval by user {$user->id}");

            // Prevent self-approval
            if ($leave->user_id == $user->id) {
                Log::warning("User {$user->id} attempted to approve their own leave.");
                abort(403, 'You cannot approve your own leave request.');
            }

            // Supervisor stage
            if ($leave->supervisor_required && $leave->supervisor_status === 'pending') {
                if ($user->id !== $leave->supervisor_id) {
                    Log::warning("User {$user->id} is not the assigned supervisor for leave ID {$leave->id}.");
                    abort(403, 'You are not authorized to approve this leave.');
                }

                $leave->update([
                    'status' => 'approved',
                    'approved_by' => $user->id,
                    'hr_approved' => true,
                    'hr_approved_at' => now(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'approved_at' => now(),
                ]);

                Log::info("Leave ID {$leave->id} approved by Supervisor ID {$user->id}");
                toastr()->success('Leave approved as Supervisor.');
                return redirect()->back();
            }

            // HR/Admin stage
            if ($user->hasRole(['admin', 'hr'])) {
                if ($leave->supervisor_required && $leave->supervisor_status !== 'approved') {
                    Log::notice("HR/Admin ID {$user->id} attempted to approve leave ID {$leave->id} before supervisor approval.");
                    toastr()->warning('Supervisor must approve first.');
                    return redirect()->back();
                }

                $leave->update([
                    'status' => 'approved',
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                ]);

                Log::info("Leave ID {$leave->id} fully approved by HR/Admin ID {$user->id}");

                event(new LeaveApproved($leave));
                toastr()->success('Leave approved successfully.');
                return redirect()->back();
            }

            Log::warning("User {$user->id} without proper role attempted to approve leave ID {$leave->id}");
            abort(403, 'You are not authorized to approve this leave.');
        } catch (\Exception $e) {
            Log::error('Leave approval failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
                'leave_id' => $id ?? null,
            ]);

            toastr()->error('Leave approval failed.');
            return redirect()->back();
        }
    }





    public function reject(Request $request, $id)
    {
        try {
            $leave = Leave::findOrFail($id);
            $user = Auth::user();

            // Validate rejection reason
            $request->validate([
                'rejection_reason' => 'required|string|max:1000',
            ]);

            if ($leave->supervisor_required && $leave->supervisor_status === 'pending') {
                // Only assigned supervisor can reject at supervisor stage
                if ($user->id !== $leave->supervisor_id) {
                    abort(403, 'Only the supervisor can reject at this stage.');
                }

                $leave->update([
                    'supervisor_status' => 'rejected',
                    'status' => 'rejected',
                    'rejection_reason' => $request->rejection_reason,
                    'supervisor_rejected_at' => now(),
                    'rejected_by' => $user->id,
                    'rejected_at' => now(),
                ]);
            } elseif ($user->hasRole(['admin', 'hr'])) {
                // HR/Admin can only reject if supervisor approved or not required
                if ($leave->supervisor_required && $leave->supervisor_status !== 'approved') {
                    toastr()->warning('Supervisor approval is required before HR/Admin can reject.');
                    return redirect()->back();
                }

                $leave->update([
                    'status' => 'rejected',
                    'rejection_reason' => $request->rejection_reason,
                    'rejected_by' => $user->id,
                    'rejected_at' => now(),
                ]);
            } else {
                abort(403, 'Unauthorized to reject leave.');
            }

            // Restore leave balance (make sure this method properly re-adds leave days)
            $this->leaveService->restoreLeaveBalance($leave);

            // Log rejection action
            $this->leaveService->logAction($leave, ['user' => $user], 'Leave rejected.');

            // Fire LeaveRejected event (to notify, etc)
            event(new LeaveRejected($leave));

            toastr()->success('Leave rejected.');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Leave rejection failed: ' . $e->getMessage());
            toastr()->error('Leave rejection failed.');
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
            ->where('supervisor_status', 'pending')
            ->where('user_id', '!=', $user->id) // exclude own leaves
            ->latest()
            ->paginate(10);

        return view('supervisor.leaves.supervisor_pending', compact('leaves'));
    }


    /**
     * List leaves pending HR/Admin final approval.
     */
    public function hrPending()
    {
        $leaves = Leave::with(['user', 'leaveType'])
            ->where(function ($query) {
                $query->where('supervisor_required', false) // no supervisor needed
                    ->orWhere('supervisor_status', 'approved'); // OR supervisor approved
            })
            ->where('status', 'pending') // still pending final approval
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('admin.leaves.hr_pending', compact('leaves'));
    }


public function approvedLeaveStatus(Request $request)
{
    $today = now()->toDateString();
    $view = $request->get('view', 'current'); // 'current' or 'upcoming'
$departments = Department::all();
$leaveTypes = LeaveType::all();
    $leaves = [];
    // Determine which view to show
    if ($request->has('view') && $request->get('view') === 'upcoming') {
        $view = 'upcoming';
    } else {
        $view = 'current';
    }
    // Fetch leaves based on the selected view
    if ($view === 'upcoming') {
        $leaves = Leave::where('status', 'approved')
            ->whereDate('start_date', '>', $today)
            ->with(['user.department', 'leaveType'])
            ->paginate(10);
    } else {
        $leaves = Leave::where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->with(['user.department', 'leaveType'])
            ->paginate(10);
    }

    return view('admin.leaves.leave-status', compact('leaves','departments','leaveTypes', 'view'));
}



    /**
     * List users currently on leave under this supervisor.
     */
    public function supervisorOnLeave()
    {
        $supervisor = Auth::user();

        // Ensure user has the supervisor role (adjust casing as needed)
        if (!$supervisor->hasRole('supervisor')) {
            abort(403, 'Unauthorized.');
        }

        // Fetch leaves active today, where leave's user has this supervisor
        $leaves = Leave::currentlyOnLeave()
            ->whereHas('user', function ($query) use ($supervisor) {
                $query->where('supervisor_id', $supervisor->id);
            })
            ->with(['user', 'leaveType'])
            ->latest()
            ->paginate(10);

        return view('supervisor.leaves.on_leave', compact('leaves'));
    }

    public function approvedLeaves()
    {
        $leaves = Leave::with(['user', 'leaveType'])
            ->where('status', 'approved')
            ->orderByDesc('approved_at')
            ->paginate(15); 

        return view('admin.leaves.hr_pending', compact('leaves'));
    }
}
