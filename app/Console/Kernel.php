<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected $commands = [
        Commands\JobRemover::class,
        Commands\NotStartedUpcomingJobStatusSwitcher::class,
        Commands\OpenJobStatusSwitcher::class,
        Commands\UpdateBiddedJobStatus::class,
        Commands\GenerateBiddingList::class,
        Commands\AwardBiddedJob::class,
        Commands\StrikeAndFlagForJobNotAccepting::class,
        Commands\UpdateNotAcceptedBiddedJobStatus::class,

    ];


    protected function schedule(Schedule $schedule)
    {
        $schedule->command('jobRemover:cron')->everyMinute();
        $schedule->command('openJobStatusSwitcher:cron')->everyMinute();
        $schedule->command('notStartedUpcomingJobStatusSwitcher:cron')->everyMinute();
        $schedule->command('updateBiddedJobStatus:cron')->everyMinute();
        $schedule->command('generateBiddingList:cron')->everyThreeMinutes();
        $schedule->command('awardBiddedJob:cron')->everyMinute();
        $schedule->command('strikeAndFlagForJobNotAccepting:cron')->everyTwoHours();
        $schedule->command('updateNotAcceptedBiddedJobStatus:cron')->everyFiveMinutes();
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
