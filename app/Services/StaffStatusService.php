<?php

namespace App\Services;

use App\Models\User;
use App\Models\StaffStatus;
use Illuminate\Support\Facades\Log;

class StaffStatusService
{
    protected array $statusMap = [
        'AVL' => 'Available',
        'NAV' => 'Not Available',
        'BRK' => 'On Break',
        'STO' => 'Stepped Out',
        'LV'  => 'On Leave',
        'ABS' => 'Absent',
    ];

    public function handleClockIn(User $user)
    {
        $this->updateStatus($user, 'AVL');
    }

    public function handleClockOut(User $user)
    {
        $this->updateStatus($user, 'NAV');
    }

    public function handleStepOut(User $user)
    {
        $this->updateStatus($user, 'STO');
    }

    public function handleReturn(User $user)
    {
        $this->updateStatus($user, 'AVL');
    }

    public function handleLeave(User $user)
    {
        $this->updateStatus($user, 'LV');
    }

    public function handleAbsent(User $user)
    {
        $this->updateStatus($user, 'ABS');
    }

    protected function updateStatus(User $user, string $statusCode)
    {
        try {
            // Get or create the StaffStatus record
            $status = StaffStatus::firstOrCreate(
                ['code' => $statusCode],
                ['name' => $this->statusMap[$statusCode]]
            );

            // Update user's current status
            $user->staff_status_id = $status->id;
            $user->save();
        } catch (\Exception $e) {
            Log::error("Failed to update staff status: " . $e->getMessage());
        }
    }

    // Optional helper to get human-readable status
    public function getStatusName(User $user): string
    {
        return $user->staffStatus?->name ?? 'Unknown';
    }
}
