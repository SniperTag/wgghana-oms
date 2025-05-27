<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LeaveService
{
    /**
     * Calculate working days excluding weekends.
     */
    public function calculateWorkingDays($startDate, $endDate)
    {
        $period = CarbonPeriod::create(Carbon::parse($startDate), Carbon::parse($endDate));
        $workingDays = 0;

        foreach ($period as $date) {
            if (!$date->isWeekend()) {
                $workingDays++;
            }
        }

        return $workingDays;
    }
}
