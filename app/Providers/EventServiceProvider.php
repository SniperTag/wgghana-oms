<?php

namespace App\Providers;

use App\Models\User;
use App\Events\StaffAbsent;
use App\Events\StaffOnLeave;
use App\Events\LeaveApproved;
use App\Events\LeaveRejected;
use App\Events\StaffReturned;
use App\Events\StaffClockedIn;
use App\Events\StaffClockedOut;
use App\Events\StaffSteppedOut;
use App\Observers\UserObserver;
use App\Listeners\LogSentEmails;
use Illuminate\Mail\Events\MessageSent;
use App\Listeners\UpdateStaffStatusListener;
use App\Listeners\SendLeaveApprovalNotification;
use App\Listeners\SendLeaveRejectionNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */
    protected $listen = [
        MessageSent::class => [
            LogSentEmails::class,
        ],
        LeaveApproved::class => [
            SendLeaveApprovalNotification::class,
        ],
        LeaveRejected::class => [
            SendLeaveRejectionNotification::class,
        ],

        StaffClockedIn::class   => [UpdateStaffStatusListener::class],
    StaffClockedOut::class  => [UpdateStaffStatusListener::class],
    StaffSteppedOut::class  => [UpdateStaffStatusListener::class],
    StaffReturned::class    => [UpdateStaffStatusListener::class],
    StaffOnLeave::class     => [UpdateStaffStatusListener::class],
    StaffAbsent::class      => [UpdateStaffStatusListener::class],
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         User::observe(UserObserver::class);
    }
}
