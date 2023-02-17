<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        Log::info('Schedule Running '. \Carbon\Carbon::now());
        $schedule->command('dictionary:update_status')
            ->timezone(Carbon::now()->timezoneName)
            ->everyFiveMinutes()->onFailure(function () {
                Log::info('Schedule Failed '. \Carbon\Carbon::now());
            });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
