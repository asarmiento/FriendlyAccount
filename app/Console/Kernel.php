<?php

namespace AccountHon\Console;

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
        \AccountHon\Console\Commands\Inspire::class,
        \AccountHon\Console\Commands\seanalert::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        $schedule->command('update-saldo')
                 ->dailyAt('12:00');
        $schedule->command('update-saldo')
                 ->dailyAt('11:00');
        $schedule->command('update-saldo')
                 ->dailyAt('10:00');
        $schedule->command('update-saldo')
                 ->dailyAt('09:00');
        $schedule->command('update-saldo')
                 ->dailyAt('08:00');
        $schedule->command('update-saldo')
                 ->dailyAt('07:00');
        $schedule->command('update-saldo')
                 ->dailyAt('00:00');
    }
}
