<?php

namespace App\Observers;

use App\Models\User;
use App\Models\LeavePolicy;
use App\Models\LeaveBalance;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
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

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Ensure the role is synced to the `users` table
        if ($user->roles->isNotEmpty()) {
            $user->role = $user->roles->first()->name; // Get the first role
            $user->save();
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
