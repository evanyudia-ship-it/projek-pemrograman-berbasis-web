<?php

namespace App\Console\Commands;

use App\Jobs\ProcessAutoCompleteBookings;
use Illuminate\Console\Command;

class AutoCompleteCommand extends Command
{
    protected $signature = 'booking:auto-complete';
    protected $description = 'Auto-complete bookings that have ended';

    public function handle(): int
    {
        $this->info('Checking for bookings to auto-complete...');

        ProcessAutoCompleteBookings::dispatch();

        $this->info('Auto-complete job dispatched successfully.');
        return Command::SUCCESS;
    }
}
