<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        // Commands\Inspire::class,
        // Commands\SendBirthDayEmail::class,
        Commands\ResetTimers::class,
        Commands\LogReminder::class,
        Commands\WeekReport::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('reset:timers')->dailyAt('24:00');
        // $schedule->command('work:reminder')->dailyAt('23:55');
        $schedule->command('week:report')->dailyAt('23:55');
        // $schedule->command('email:birthday')->everyMinute();
        $date = Carbon::now()->toW3cString();
        $environment = env('APP_ENV');
        $site_name = env('APP_SITE','Teamwork');
        //$schedule->command("db:backup --database=pgsql --destination=local --destinationPath=/{$environment}/{$site_name}_{$environment}_{$date} --compression=gzip")->daily();
    }
}
