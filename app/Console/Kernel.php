<?php

namespace App\Console;

use App\Jobs\AfterGame;
use App\Jobs\AutoGameSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        // $schedule->command('run:resetdb')->dailyAt('02:00');
        $schedule->job(new AutoGameSchedule)->dailyAt('00:05');
        $schedule->job(new AutoGameSchedule)->dailyAt('23:45');
        $schedule->job(new AfterGame)->hourly();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
