<?php

namespace App\Console;

use App\Console\Commands\SendAppointmentReminder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SendAppointmentReminder::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('appointments:reminder')->everyMinute(10);
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
