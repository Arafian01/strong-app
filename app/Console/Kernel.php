<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\GenerateTagihan::class,
    ];    
    
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tagihan:generate')->everyMinute();
    }

    protected $routeMiddleware = [
        
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        
    ];

}
