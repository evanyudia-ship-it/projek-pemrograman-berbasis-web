<?php

namespace App\Console\Commands;

use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupNotifications extends Command
{
    protected $signature = 'notification:cleanup';
    protected $description = 'Clean up old read notifications';

    public function handle(): int
    {
        $this->info('Cleaning up old notifications...');

        // Delete read notifications older than 30 days
        $deleted = Notifikasi::where('status', 'sudah_dibaca')
            ->where('created_at', '<', Carbon::now()->subDays(30))
            ->delete();

        $this->info("Deleted {$deleted} old read notifications.");
        return Command::SUCCESS;
    }
}
