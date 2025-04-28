<?php

namespace App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        $this->app->booted(function () {
            $schedule = app(Schedule::class);
            $schedule->command('tagihan:generate')->monthlyOn(1, '00:00');
        });
        
        Gate::define('role-admin', function ($user){
            return $user->role === 'admin';
        });
        
        Gate::define('access-laporan', function ($user) {
            return in_array($user->role, ['admin', 'owner']);
        });
    }
}
