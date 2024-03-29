<?php

namespace App\Console;

use App\Console\Commands\BuildSearchIndex;
use App\Console\Commands\CheckGeocodeErrors;
use App\Console\Commands\ClientInfoUpdate;
use App\Console\Commands\Upgrade;
use App\Console\Commands\UpgradeCitynexus;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Upgrade::class,
        UpgradeCitynexus::class,
        ClientInfoUpdate::class,
        BuildSearchIndex::class,
        CheckGeocodeErrors::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
