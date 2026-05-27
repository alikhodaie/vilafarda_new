<?php

namespace App\Console;

use App\Jobs\CancelUndeterminedOrders;
use App\Jobs\CancelUnpaidOrders;
use App\Jobs\ClearSettingAndProvinceCache;
use App\Jobs\CloseTicketJob;
use App\Jobs\ExpireFastReserve;
use App\Jobs\SendAfterResidenceSMS;
use App\Jobs\SendBeforResidenceSMS;
use App\Jobs\SetDoneStatusForOrders;
use App\Jobs\SetInRentStatusForOrders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new ClearSettingAndProvinceCache())->dailyAt('00:00');
        $schedule->job(new CloseTicketJob())->dailyAt('00:00');
        $schedule->job(new SendBeforResidenceSMS())->dailyAt('18:00');
        $schedule->job(new SendAfterResidenceSMS())->dailyAt('18:00');
        $schedule->job(new SetInRentStatusForOrders())->dailyAt('23:30');
        $schedule->job(new SetDoneStatusForOrders())->dailyAt('23:30');
        $schedule->job(new CancelUnpaidOrders())->everyMinute();
        $schedule->job(new CancelUndeterminedOrders())->everyMinute();
        $schedule->job(new ExpireFastReserve())->dailyAt('00:05');
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
