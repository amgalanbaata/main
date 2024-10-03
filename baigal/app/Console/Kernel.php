<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    
    protected $commands = [
        Commands\DailyQuestionnaireEnd::class,
        Commands\DailyRemind::class,
        Commands\DailyTicketRemove::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('quote:questionnaire');
        $schedule->command('quote:remind');
        $schedule->command('quote:ticket');
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
