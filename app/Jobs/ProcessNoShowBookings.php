<?php

namespace App\Jobs;

use App\Services\BookingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessNoShowBookings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    public function handle(BookingService $bookingService): void
    {
        try {
            Log::info('Starting ProcessNoShowBookings job...');

            $count = $bookingService->processNoShows();

            if ($count > 0) {
                Log::info("Processed {$count} no-show bookings.");
            } else {
                Log::info("No no-show bookings found.");
            }
        } catch (\Exception $e) {
            Log::error("Error processing no-show bookings: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessNoShowBookings job failed: ' . $exception->getMessage());
    }
}
