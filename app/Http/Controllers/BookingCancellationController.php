<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;
use App\Traits\AuthenticatesUser;
use Illuminate\Http\Request;

class BookingCancellationController extends Controller
{
    use AuthenticatesUser;

    public function create($id)
    {
        $booking = Booking::findOrFail($id);
        $userId = $this->currentUserId();

        if ($userId != $booking->user_id) {
            abort(403);
        }

        if (!$booking->canBeCancelledByUser()) {
            return redirect()->route('bookings.index')->with('error', 'Booking tidak dapat dibatalkan.');
        }

        return view('bookings.cancel', compact('booking'));
    }

    public function store(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $userId = $this->currentUserId();

        if ($userId != $booking->user_id) {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        if (!$booking->canBeCancelledByUser()) {
            return back()->with('error', 'Booking tidak dapat dibatalkan.');
        }

        // Gunakan BookingService dengan transaction
        $bookingService = app(BookingService::class);
        $bookingService->cancel($booking, $request->reason, $userId);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibatalkan.');
    }
}
