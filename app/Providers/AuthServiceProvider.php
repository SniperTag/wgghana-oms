<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Leave;
use App\Policies\LeavePolicy;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }
     /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Leave::class => LeavePolicy::class,
    ];


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
