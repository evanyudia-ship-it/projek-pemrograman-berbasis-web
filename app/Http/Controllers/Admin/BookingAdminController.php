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
}