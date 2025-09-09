<?php
namespace App\Services;

use App\Models\LeaveBalance;
use App\Models\LeavePolicy;

class LeaveBalanceService
{
    public static function generateForUser($user)
    {
        $currentYear = now()->year;

        $policies = LeavePolicy::where('role_id', $user->role_id)
                               ->where('department_id', $user->department_id)
                               ->where('year', $currentYear)
                               ->get();

        foreach ($policies as $policy) {
            LeaveBalance::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'leave_type_id' => $policy->leave_type_id,
                    'year' => $currentYear,
                ],
                [
                    'total_days' => $policy->total_days,
                    'used_days' => 0,
                    'remaining_days' => $policy->total_days,
                ]
            );
        }
    }
}
