<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of user's bookings.
     */
    public function index()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $bookings = Booking::with(['room', 'user'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => Booking::where('user_id', $userId)->count(),
            'pending' => Booking::where('user_id', $userId)->where('status', 'pending')->count(),
            'approved' => Booking::where('user_id', $userId)->where('status', 'approved')->count(),
            'no_show' => Booking::where('user_id', $userId)->where('status', 'no_show')->count(),
        ];

        return view('bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Show form to create a new booking.
     */
    public function create()
    {
        $rooms = Room::where('status', 'Tersedia')->get();
        return view('bookings.create', compact('rooms'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'kegiatan' => 'required|string|max:150',
            'tujuan' => 'required|string|max:500',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login.');
        }

        // Cek ketersediaan ruang
        $room = Room::find($request->room_id);
        if (!$room->isAvailableAt($request->tanggal, $request->jam_mulai, $request->jam_selesai)) {
            return back()->withInput()->with('error', 'Ruang tidak tersedia pada waktu yang dipilih.');
        }

        // Generate booking code
        $lastBooking = Booking::orderBy('id', 'desc')->first();
        $nextId = $lastBooking ? $lastBooking->id + 1 : 1;
        $bookingCode = 'BK-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $start = Carbon::parse($request->tanggal . ' ' . $request->jam_mulai);
        $end = Carbon::parse($request->tanggal . ' ' . $request->jam_selesai);
        $durasiMenit = $start->diffInMinutes($end);

        // Deadline check-in: 30 menit setelah jam mulai
        $checkinDeadline = Carbon::parse($request->tanggal . ' ' . $request->jam_mulai)->addMinutes(30);

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => $userId,
            'room_id' => $request->room_id,
            'kegiatan' => $request->kegiatan,
            'tujuan' => $request->tujuan,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'durasi_menit' => $durasiMenit,
            'priority_level' => 'Medium', // default
            'status' => 'pending',
            'check_in_status' => 'belum_checkin',
            'checkin_deadline' => $checkinDeadline,
            'is_penalty_applied' => false,
        ]);

        // Catat histori (opsional)
        // BookingHistory::create([...]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking berhasil diajukan! Menunggu persetujuan admin.');
    }

    /**
     * Display the specified booking.
     */
    public function show($id)
    {
        $booking = Booking::with(['room', 'user', 'approvedBy', 'cancelledBy'])
            ->findOrFail($id);

        // Pastikan hanya pemilik atau admin yang bisa melihat
        if (session('user_id') != $booking->user_id && !in_array(session('user_role'), ['admin', 'superadmin'])) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        // Hanya bisa edit jika status pending dan milik sendiri
        if (session('user_id') != $booking->user_id || $booking->status != 'pending') {
            abort(403);
        }
        $rooms = Room::where('status', 'Tersedia')->get();
        return view('bookings.edit', compact('booking', 'rooms'));
    }

    /**
     * Update the specified booking.
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        if (session('user_id') != $booking->user_id || $booking->status != 'pending') {
            abort(403);
        }

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'kegiatan' => 'required|string|max:150',
            'tujuan' => 'required|string|max:500',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Cek ketersediaan (kecuali jika ruang dan waktu sama persis)
        $room = Room::find($request->room_id);
        if (!$room->isAvailableAt($request->tanggal, $request->jam_mulai, $request->jam_selesai, $booking->id)) {
            return back()->withInput()->with('error', 'Ruang tidak tersedia pada waktu yang dipilih.');
        }

        $start = Carbon::parse($request->tanggal . ' ' . $request->jam_mulai);
        $end = Carbon::parse($request->tanggal . ' ' . $request->jam_selesai);
        $durasiMenit = $start->diffInMinutes($end);

        $booking->update([
            'room_id' => $request->room_id,
            'kegiatan' => $request->kegiatan,
            'tujuan' => $request->tujuan,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'durasi_menit' => $durasiMenit,
            // deadline check-in diupdate juga
            'checkin_deadline' => Carbon::parse($request->tanggal . ' ' . $request->jam_mulai)->addMinutes(30),
        ]);

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Booking berhasil diperbarui.');
    }

    /**
     * Check-in action.
     */
    public function checkin(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        if (session('user_id') != $booking->user_id) {
            abort(403);
        }

        if (!$booking->canCheckIn()) {
            return back()->with('error', 'Booking tidak dapat di-check-in. Status: ' . $booking->status);
        }

        $now = Carbon::now();
        $startTime = Carbon::parse($booking->tanggal->format('Y-m-d') . ' ' . $booking->jam_mulai->format('H:i:s'));
        $checkinStatus = $now->lte($startTime) ? 'checkin_tepat_waktu' : 'checkin_terlambat';

        $booking->update([
            'check_in_status' => $checkinStatus,
            'check_in_at' => $now,
            'status' => 'approved', // tetap approved, atau bisa lanjut
        ]);

        // Tambahkan reputasi jika check-in tepat waktu (contoh)
        // $booking->user->addReputationPoints(5, 'Check-in tepat waktu', null, $booking->id);

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Check-in berhasil!');
    }

    /**
     * Show booking history (completed, cancelled, no-show).
     */
    public function history()
    {
        $userId = session('user_id');
        $bookings = Booking::with(['room'])
            ->where('user_id', $userId)
            ->whereIn('status', ['completed', 'cancelled', 'no_show', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.history', compact('bookings'));
    }

    /**
     * Cancel booking (old method, may be replaced by cancellation controller).
     * We keep for backward compatibility.
     */
    public function cancel(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        if (session('user_id') != $booking->user_id) {
            abort(403);
        }

        if (!$booking->canBeCancelledByUser()) {
            return back()->with('error', 'Booking tidak dapat dibatalkan.');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->input('reason', 'Dibatalkan oleh pemohon'),
            'cancelled_by' => session('user_id'),
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking dibatalkan.');
    }
}