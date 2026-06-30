<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingAdminController extends Controller
{
    /**
     * List all bookings for admin.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('kegiatan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show booking detail for admin.
     */
    public function show($id)
    {
        $booking = Booking::with(['user', 'room', 'approvedBy', 'cancelledBy', 'histories'])
            ->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    use App\Services\ReputationService;

    /**
     * Mark booking as fake (admin only).
     */
    public function markFakeBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'pending' && $booking->status !== 'approved') {
            return back()->with('error', 'Booking tidak dapat ditandai fiktif.');
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($booking, $request) {
            // Update booking status
            $booking->update([
                'status' => 'rejected',
                'catatan_admin' => 'Booking Fiktif: ' . $request->reason,
                'cancellation_reason' => 'Booking Fiktif: ' . $request->reason,
                'disetujui_oleh' => session('user_id'),
                'disetujui_at' => now(),
            ]);

            // Apply reputation penalty
            $reputationService = app(ReputationService::class);
            $reputationService->apply(
                $booking->user,
                'FAKE_BOOKING',
                $booking,
                'Booking fiktif: ' . $request->reason,
                session('user_id')
            );

            // Send notification
            NotificationHelper::send(
                $booking->user_id,
                'Booking Dinyatakan Fiktif ❌',
                "Booking {$booking->booking_code} dinyatakan fiktif. Reputasi Anda berkurang 20 poin. Alasan: {$request->reason}",
                'danger',
                'bookings',
                (string) $booking->id
            );
        });

        return back()->with('success', "Booking {$booking->booking_code} ditandai fiktif. Reputasi user berkurang 20 poin.");
    }
}
