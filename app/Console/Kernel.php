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
        \App\Console\Commands\ExpenseNotifications::class,
        \App\Console\Commands\MembersSportsExpiryNotifications::class,
        \App\Console\Commands\MembersPendingPaymentNotifications::class,
        \App\Console\Commands\EmployeePendingSalaryNotifications::class,
        \App\Console\Commands\EmployeeExpiryNotifications::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notification:expense')
                 ->everyMinute();
        $schedule->command('notification:members_sports_expiry')
                 ->everyMinute();
        $schedule->command('notification:members_pending_payment')
                 ->everyMinute();
        $schedule->command('notification:employee_pending_salary')
                 ->everyMinute();
        $schedule->command('notification:employee_expiry')
                 ->everyMinute();
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
