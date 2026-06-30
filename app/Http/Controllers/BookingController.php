<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomSchedule;
use App\Traits\AuthenticatesUser;
use App\Helpers\PriorityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class BookingController extends Controller
{
    use AuthenticatesUser;

    /**
     * Display a listing of user's bookings.
     */
    public function index()
    {
        $userId = $this->currentUserId();
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
     * Check if user can create booking based on reputation.
     */
    private function checkReputation(User $user): ?string
    {
        $reputationService = app(\App\Services\ReputationService::class);

        // Cek apakah user bisa booking
        if (!$reputationService->canBook($user)) {
            return 'Akun Anda diblokir karena reputasi rendah. Hubungi admin untuk informasi lebih lanjut.';
        }

        // Cek apakah sudah mencapai max active bookings
        if ($reputationService->hasReachedMaxActiveBookings($user)) {
            $max = $reputationService->getMaxActiveBookings($user);
            return "Anda sudah mencapai batas maksimal booking aktif ({$max} booking). Selesaikan booking yang sedang berjalan terlebih dahulu.";
        }

        return null;
    }

    /**
     * Show form to create a new booking.
     */
    public function create()
    {
        $rooms = Room::where('status', 'Tersedia')->get();
        $prioritas = PriorityHelper::getActivitiesGroupedByPriority();
        return view('bookings.create', compact('rooms', 'prioritas'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'kegiatan' => 'required|string|max:150',
            'jenis_kegiatan' => 'required|string|in:' . implode(',', array_keys(PriorityHelper::getPriorities())),
            'tujuan' => 'required|string|max:500',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $userId = $this->currentUserId();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login.');
        }

        $user = User::find($userId);

        // CEK REPUTASI
        $reputationError = $this->checkReputation($user);
        if ($reputationError) {
            return back()->withInput()->with('error', $reputationError);
        }

        // Cek ketersediaan ruang
        $room = Room::find($request->room_id);
        if (!$room->isAvailableAt($request->tanggal, $request->jam_mulai, $request->jam_selesai)) {
            return back()->withInput()->with('error', 'Ruang tidak tersedia pada waktu yang dipilih.');
        }

        // Gunakan BookingService dengan transaction
        $bookingService = app(\App\Services\BookingService::class);
        $booking = $bookingService->create($request->all(), $userId);

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

        $userId = $this->currentUserId();
        $role = $this->currentRole();

        if ($userId != $booking->user_id && !in_array($role, ['admin', 'superadmin'])) {
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
        $userId = $this->currentUserId();
        // Hanya bisa edit jika status pending dan milik sendiri
        if ($userId != $booking->user_id || $booking->status != 'pending') {
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
        $userId = $this->currentUserId();

        if ($userId != $booking->user_id || $booking->status != 'pending') {
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

        // Cek ketersediaan
        $room = Room::find($request->room_id);
        if (!$room->isAvailableAt($request->tanggal, $request->jam_mulai, $request->jam_selesai, $booking->id)) {
            return back()->withInput()->with('error', 'Ruang tidak tersedia pada waktu yang dipilih.');
        }

        // Gunakan BookingService dengan transaction
        $bookingService = app(\App\Services\BookingService::class);
        $booking = $bookingService->update($booking, $request->all());

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Booking berhasil diperbarui.');
    }

    /**
     * Check-in action.
     */
    public function checkin(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $userId = $this->currentUserId();

        if ($userId != $booking->user_id) {
            abort(403);
        }

        if (!$booking->canCheckIn()) {
            return back()->with('error', 'Booking tidak dapat di-check-in. Status: ' . $booking->status);
        }

        // Gunakan BookingService dengan transaction
        $bookingService = app(\App\Services\BookingService::class);
        $booking = $bookingService->checkIn($booking);

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Check-in berhasil!');
    }

    /**
     * Complete booking (after usage).
     */
    public function complete(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $userId = $this->currentUserId();

        // Cek apakah booking milik user yang sedang login
        if ($userId != $booking->user_id) {
            abort(403);
        }

        // Cek apakah booking sedang berlangsung
        if ($booking->status !== 'ongoing') {
            return back()->with('error', 'Booking tidak dapat diselesaikan. Status: ' . $booking->status);
        }

        // Complete booking dengan transaction
        $bookingService = app(\App\Services\BookingService::class);
        $booking = $bookingService->complete($booking);

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Booking berhasil diselesaikan! Terima kasih telah menggunakan ruangan.');
    }

    /**
     * Show booking history (completed, cancelled, no-show).
     */
    public function history()
    {
        $userId = $this->currentUserId();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

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
        $userId = $this->currentUserId();

        if ($userId != $booking->user_id) {
            abort(403);
        }

        if (!$booking->canBeCancelledByUser()) {
            return back()->with('error', 'Booking tidak dapat dibatalkan.');
        }

        $reason = $request->input('reason', 'Dibatalkan oleh pemohon');

        // Gunakan BookingService dengan transaction
        $bookingService = app(\App\Services\BookingService::class);
        $bookingService->cancel($booking, $reason, $userId);

        return redirect()->route('bookings.index')->with('success', 'Booking dibatalkan.');
    }
}
