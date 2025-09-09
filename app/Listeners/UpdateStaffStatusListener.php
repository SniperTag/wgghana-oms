<?php

namespace App\Listeners;

use App\Events\StaffClockedIn;
use App\Events\StaffClockedOut;
use App\Events\StaffSteppedOut;
use App\Events\StaffReturned;
use App\Events\StaffOnLeave;
use App\Events\StaffAbsent;
use App\Services\StaffStatusService;

class UpdateStaffStatusListener
{
    protected $statusService;


    public function __construct(StaffStatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    public function handle($event)
    {
        // Check the type of event and call the appropriate method
        $user = $event->user;

        switch (get_class($event)) {
            case StaffClockedIn::class:
                $this->statusService->handleClockIn($user);
                break;

            case StaffClockedOut::class:
                $this->statusService->handleClockOut($user);
                break;

            case StaffSteppedOut::class:
                $this->statusService->handleStepOut($user);
                break;

            case StaffReturned::class:
                $this->statusService->handleReturn($user);
                break;

            case StaffOnLeave::class:
                $this->statusService->handleLeave($user);
                break;

            case StaffAbsent::class:
                $this->statusService->handleAbsent($user);
                break;

            default:
                // Unknown event, do nothing or log warning
                break;
        }
    }
}
