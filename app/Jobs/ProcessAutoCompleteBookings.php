<?php

namespace App\Jobs;

use App\Services\BookingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessAutoCompleteBookings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    public function handle(BookingService $bookingService): void
    {
        try {
            Log::info('Starting ProcessAutoCompleteBookings job...');

            $count = $bookingService->processAutoComplete();

            if ($count > 0) {
                Log::info("Auto-completed {$count} bookings.");
            } else {
                Log::info("No bookings to auto-complete.");
            }
        } catch (\Exception $e) {
            Log::error("Error processing auto-complete bookings: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessAutoCompleteBookings job failed: ' . $exception->getMessage());
    }
}
