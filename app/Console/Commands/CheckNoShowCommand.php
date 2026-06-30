<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class CheckNoShowCommand extends Command
{
    protected $signature = 'booking:check-no-show {--sync : Process directly without queue}';
    protected $description = 'Check and process no-show bookings';

    public function handle(BookingService $bookingService): int
    {
        $this->info('Checking for no-show bookings...');

        if ($this->option('sync')) {
            // Process langsung tanpa queue
            $count = $bookingService->processNoShows();
            $this->info("Processed {$count} no-show bookings.");
        } else {
            // Dispatch ke queue (default)
            \App\Jobs\ProcessNoShowBookings::dispatch();
            $this->info('No-show check job dispatched successfully.');
        }

        return Command::SUCCESS;
    }
}
