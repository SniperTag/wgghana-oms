<?php

namespace App\Services;

use App\Models\Leave;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\AttendanceRecord;
use App\Models\User;

class UserDataService
{
    /**
     * Fetch leave and attendance data for a given user with optional filter.
     *
     * @param \App\Models\User $user
     * @param string|null $filter ('this_month', etc.)
     * @return array
     */
public function getLeaveAndAttendanceData($user, $filter = null, $from = null, $to = null)
{
    $hasFilter = !empty($filter) || ($from && $to);

    // Leaves Query + Filters (same as before)...
    $leavesQuery = Leave::with(['user', 'leaveType', 'approvedByUser'])
        ->where('user_id', $user->id);

    if ($filter === 'this_month') {
        $leavesQuery->whereBetween('start_date', [
            now()->startOfMonth()->toDateString(),
            now()->endOfMonth()->toDateString()
        ]);
    } elseif ($filter === 'this_week') {
        $leavesQuery->whereBetween('start_date', [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString()
        ]);
    } elseif ($filter === 'today') {
        $leavesQuery->whereDate('start_date', now()->toDateString());
    }

    if ($from && $to) {
        $leavesQuery->whereBetween('start_date', [$from, $to]);
    }

    $leaves = $leavesQuery->latest()->paginate(10);

    // Leave stats
    $pendingCount = Leave::where('user_id', $user->id)->where('status', 'pending')->count();
    $approvedCount = Leave::where('user_id', $user->id)->where('status', 'approved')->count();
    $rejectedCount = Leave::where('user_id', $user->id)->where('status', 'rejected')->count();
    $onLeaveCount = Leave::currentlyOnLeave()->where('user_id', $user->id)->count();

    // Attendance Query + Filters
    $attendanceQuery = AttendanceRecord::with('user.department')
        ->where('user_id', $user->id);

    if ($filter === 'this_month') {
        $attendanceQuery->whereBetween('attendance_date', [
            now()->startOfMonth()->toDateString(),
            now()->endOfMonth()->toDateString()
        ]);
    } elseif ($filter === 'this_week') {
        $attendanceQuery->whereBetween('attendance_date', [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString()
        ]);
    } elseif ($filter === 'today') {
        $attendanceQuery->whereDate('attendance_date', now()->toDateString());
    }

    if ($from && $to) {
        $attendanceQuery->whereBetween('attendance_date', [$from, $to]);
    }

    $attendanceRecords = $attendanceQuery->orderBy('attendance_date', 'desc')->paginate(10);

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

    // Bundle all data inside the 'records' array:
    $records = [
        'leaves' => $leaves,
        'pendingCount' => $pendingCount,
        'approvedCount' => $approvedCount,
        'rejectedCount' => $rejectedCount,
        'onLeaveCount' => $onLeaveCount,
        'leaveBalance' => $leaveBalance,
        'totalAnnualLeaveCount' => $totalAnnualLeaveCount,
        'attendanceRecords' => $attendanceRecords,
        'hasFilter' => $hasFilter,
    ];

    return $records;
}



}
