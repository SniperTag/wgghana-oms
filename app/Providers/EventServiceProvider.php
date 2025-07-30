<?php

namespace App\Providers;

use App\Events\LeaveApproved;
use App\Events\LeaveRejected;
use App\Listeners\LogSentEmails;
use Illuminate\Mail\Events\MessageSent;
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
        //
    }
}
