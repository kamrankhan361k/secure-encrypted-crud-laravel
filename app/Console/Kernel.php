<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\EncryptionMaintenanceCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Daily encryption integrity check at 2 AM
        $schedule->command('encryption:maintenance --check-integrity')
                 ->dailyAt('02:00')
                 ->environments(['production']);

        // Weekly encryption statistics
        $schedule->command('encryption:maintenance --stats')
                 ->weekly()
                 ->sundays()
                 ->at('06:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
