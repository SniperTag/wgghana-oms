<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class MarkAbsentAfterNoon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mark-absent-after-noon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark users as Absent if they haven\'t checked in by 12:00 PM and are not on leave';

    /**
     * Execute the console command.
     */


    public function handle()
    {

        $today = Carbon::today()->toDateString();

        $users = User::whereDoesntHave('attendanceRecords', function ($query) use ($today) {
            $query->where('attendance_date', $today);
        })->get();

        $absentCount = 0;

        foreach ($users as $user) {
            // If on leave, mark as "On Leave" instead of "Absent"
            if ($user->leaves()->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)->exists()
            ) {
                $status = 'On Leave';
            } else {
                $status = 'Absent';
            }

            AttendanceRecord::create([
                'user_id' => $user->id,
                'department_id' => $user->department_id,
                'attendance_date' => $today,
                'status' => $status,
            ]);

            $absentCount++;
        }

        $this->info("Marked $absentCount users as Absent/On Leave.");
    }
}
