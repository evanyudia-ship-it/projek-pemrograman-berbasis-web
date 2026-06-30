<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Check no-show bookings every 5 minutes (30 menit setelah jam_mulai)
        $schedule->command('booking:check-no-show')->everyFiveMinutes();

        // Auto-complete bookings every 5 minutes (1 jam setelah jam_selesai)
        $schedule->command('booking:auto-complete')->everyFiveMinutes();

        // Clean up old notifications daily
        $schedule->command('notification:cleanup')->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
