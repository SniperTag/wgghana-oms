<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AppointmentService;
use App\Services\VisitorService;
use Spatie\Activitylog\Models\Activity;
use App\Models\Activity as CustomActivity;
use App\Observers\UserObserver;
use App\Models\User;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register AppointmentService as a singleton
        $this->app->singleton(AppointmentService::class, function ($app) {
            return new AppointmentService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         User::observe(UserObserver::class);
    }
}
