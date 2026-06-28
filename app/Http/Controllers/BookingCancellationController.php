<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingCancellationController extends Controller
{
    /**
     * Show cancellation form.
     */
    public function create($id)
    {
        $booking = Booking::findOrFail($id);
        // Pastikan milik user dan bisa dibatalkan
        if (session('user_id') != $booking->user_id) {
            abort(403);
        }
        if (!$booking->canBeCancelledByUser()) {
            return redirect()->route('bookings.index')->with('error', 'Booking tidak dapat dibatalkan.');
        }

        return view('bookings.cancel', compact('booking'));
    }

    /**
     * Process cancellation with reason.
     */
    public function store(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        if (session('user_id') != $booking->user_id) {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        if (!$booking->canBeCancelledByUser()) {
            return back()->with('error', 'Booking tidak dapat dibatalkan.');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->reason,
            'cancelled_by' => session('user_id'),
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibatalkan.');
    }
}