<?php

namespace App\Console;

use App\Jobs\SubscriptionFailedRenewalJob;
use App\Jobs\SubscriptionRenewalJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->job(new SubscriptionRenewalJob())->hourly();
        $schedule->job(new SubscriptionFailedRenewalJob())->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
