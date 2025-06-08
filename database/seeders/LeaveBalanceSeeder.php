<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\LeavePolicy;

class LeaveBalanceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $leaveTypes = LeaveType::all();
        $currentYear = now()->year;

        foreach ($users as $user) {
            foreach ($leaveTypes as $leaveType) {
                // Fetch the matching policy
                $policy = LeavePolicy::where('leave_type_id', $leaveType->id)
                    ->where(function ($query) use ($user) {
                        $query->where('role_id', $user->roles->pluck('id')->first())
                              ->orWhere('department_id', $user->department_id);
                    })
                    ->first();

                // Use policy days or fallback to default
                $totalDays = $policy?->total_days ?? $leaveType->default_days ?? 15;

                // Create the balance record
                LeaveBalance::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'leave_type_id' => $leaveType->id,
                        'year' => $currentYear,
                    ],
                    [
                        'total_days' => $totalDays,
                        'used_days' => 0,
                        'remaining_days' => $totalDays,
                    ]
                );

                echo "Leave balance created for user {$user->id} - {$leaveType->name} ({$totalDays} days)\n";
            }
        }
    }
}
