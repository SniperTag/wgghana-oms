<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\HostAppointmentReminderMail;

class SendAppointmentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:reminder';
    protected $description = 'Send appointment reminders to hosts for upcoming meetings';

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     */
   public function handle()
    {
        // Fetch appointments that are rescheduled and within the next 30 minutes
        // This assumes 'rescheduled_at' is a datetime field indicating when the appointment was rescheduled
{
    $appointments = Appointment::where('status', 'rescheduled')
        ->whereBetween('date', [now()->addMinutes(29), now()->addMinutes(31)])
        ->get();

     foreach ($appointments as $appointment) {
            if ($appointment->host && $appointment->host->email) {
                Mail::to($appointment->host->email)
                    ->send(new HostAppointmentReminderMail($appointment));
            }
        }

    return Command::SUCCESS;
}

}
}
