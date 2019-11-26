<?php

namespace App\Console;

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
        Commands\BookClass::class,
        //Commands\Test::class,
        Commands\Baidu::class,
        Commands\SeoUpdate::class,
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
        $schedule->command('book:class')->dailyAt('03:00')->runInBackground()->appendOutputTo(storage_path('logs/class.log'))->withoutOverlapping();
        $schedule->command('baidu:submit')->dailyAt('12:00')->runInBackground()->appendOutputTo(storage_path('logs/baidu.log'))->withoutOverlapping();
        $schedule->command('seo:update')->cron('30 */1 * * * *')->runInBackground()->appendOutputTo(storage_path('logs/seoupdate.log'))->withoutOverlapping();
        //$schedule->command('test:test')->everyMinute()->runInBackground()->sendOutputTo(storage_path('logs/test.log'));       
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
