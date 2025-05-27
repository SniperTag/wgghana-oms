<?php
namespace App\Policies;

use App\Models\Leave;
use App\Models\User;

class LeavePolicy
{
    /**
     * Any user can view their own leaves or those they supervise/administer.
     */
    public function view(User $user, Leave $leave): bool
    {
        return $user->id === $leave->user_id // Their own leave
            || $user->id === $leave->supervisor_id // supervisor
            || $user->hasRole(['admin', 'hr']); // hr/admin
    }

    /**
     * admin, hr, and the user themselves can view lists.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'hr', 'supervisor']) || $user->leaves()->exists();
    }

    /**
     * staffs can request leave.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('staff') || $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * hr or admin can update/approve a leave.
     */
    public function update(User $user, Leave $leave): bool
    {
        return $user->hasRole(['hr', 'admin']);
    }

    /**
     * Only hr or admin can delete.
     */
    public function delete(User $user, Leave $leave): bool
    {
        return $user->hasRole(['admin', 'hr']);
    }

    /**
     * supervisors can approve if assigned as supervisor.
     */
    public function approveAssupervisor(User $user, Leave $leave): bool
    {
        return $leave->supervisor_id === $user->id;
    }

    /**
     * hr or admin can approve any.
     */
    public function approveAshroradmin(User $user, Leave $leave): bool
    {
        return $user->hasRole(['hr', 'admin']);
    }
}
