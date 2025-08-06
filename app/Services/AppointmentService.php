<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AppointmentService
{
    /**
     * Get all appointments for today.
     */
    public function getTodayAppointments(int $limit = null): Collection
    {
        $query = Appointment::whereDate('date', Carbon::today())->orderBy('time');
        return $limit ? $query->limit($limit)->get() : $query->get();
    }

    /**
     * Get all upcoming appointments after today.
     */
    public function getUpcomingAppointments(int $limit = null): Collection
    {
        $query = Appointment::whereDate('date', '>', Carbon::today())
            ->orderBy('date')
            ->orderBy('time');

        return $limit ? $query->limit($limit)->get() : $query->get();
    }
}
