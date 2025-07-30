<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Leave;
use App\Models\LeaveRequest;
use Illuminate\Console\Command;
use App\Mail\UpcomingLeaveAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendUpcomingLeaveAlerts extends Command
{
    protected $signature = 'leaves:send-upcoming-alerts';
    protected $description = 'Send daily alerts to staff whose leave starts tomorrow';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $leaves = Leave::where('start_date', $tomorrow)
            ->where('status', 'Approved')
            ->with('user', 'leaveType')
            ->get();

        foreach ($leaves as $leave) {
    if ($leave->user && $leave->user->email) {
        // Get Supervisor email (if assigned)
        $supervisorEmail = optional($leave->user->supervisor)->email;

        // Set HR email(s) - either from config or hardcoded
        $hrEmails = ['dn585632@gmail.com']; // or fetch from DB/settings

        Mail::to($leave->user->email)
            ->cc(array_filter([$supervisorEmail])) 
            ->bcc($hrEmails)
            ->send(new UpcomingLeaveAlert($leave));

        Log::info("Leave alert sent to: {$leave->user->email}, CC: {$supervisorEmail}, BCC: HR");
    }

        return Command::SUCCESS;
    }
}
}