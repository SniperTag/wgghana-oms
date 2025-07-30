<?php

namespace App\Services;

use App\Models\User;
use App\Models\Leave;
use App\Models\LeaveBalance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Holiday;
use Illuminate\Support\Facades\Notification;
use Spatie\Activitylog\Models\Activity;
use App\Notifications\LeaveSubmittedNotification;
use App\Notifications\LeaveRestoredNotification;

class LeaveService
{
    /**
     * Calculate working days excluding weekends.
     */
   

    public function calculateWorkingDays($startDate, $endDate): int
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $period = CarbonPeriod::create($start, $end);

        // Fetch all holidays between the range
        $holidays = Holiday::whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        $workingDays = collect($period)->filter(function ($date) use ($holidays) {
            return !$date->isWeekend() && !in_array($date->toDateString(), $holidays);
        });

        return $workingDays->count();
    }
    /**
     * Log leave creation with Spatie + send email notification
     */
    public function logCreation(Leave $leave, User $user)
    {
        activity()
            ->performedOn($leave)
            ->causedBy($user)
            ->withProperties(['action' => 'created', 'reason' => $leave->reason])
            ->log('Leave request submitted');

        Notification::route('mail', 'hr@example.com') // Replace with actual HR user(s) or route
            ->notify(new LeaveSubmittedNotification($leave));
    }

    /**
     * Log leave update with Spatie
     */
    public function logUpdate(Leave $leave, User $user)
    {
        activity()
            ->performedOn($leave)
            ->causedBy($user)
            ->withProperties(['action' => 'updated'])
            ->log('Leave request updated');
    }

    /**
     * General-purpose action logger
     */
    public function logAction(string $action, array $details = [])
    {
        activity('leave')
            ->causedBy(Auth::user())
            ->withProperties($details)
            ->log($action);
    }

    /**
     * Restore leave balance when leave is cancelled or rejected
     */
    public function restoreLeaveBalance(Leave $leave)
    {
        DB::transaction(function () use ($leave) {
            $balance = LeaveBalance::where('user_id', $leave->user_id)
                ->where('leave_type_id', $leave->leave_type_id)
                ->lockForUpdate()
                ->first();

            if ($balance) {
                $balance->used_days -= $leave->days_requested;
                $balance->remaining_days += $leave->days_requested;
                $balance->save();

                activity()
                    ->performedOn($balance)
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'leave_id' => $leave->id,
                        'days_restored' => $leave->days_requested,
                        'reason' => 'Leave was rejected or cancelled',
                    ])
                    ->log('Leave balance restored');

                Notification::route('mail', 'dn326045@gmail.com.com')
                    ->notify(new LeaveRestoredNotification($leave));
            }
        });
    }

    
}
