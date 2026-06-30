<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Services\BookingService;
use App\Traits\AuthenticatesUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApprovalController extends Controller
{
    use AuthenticatesUser;

    /**
     * Menampilkan daftar booking pending dan riwayat approval.
     */
    public function index()
    {
        $pending = Booking::with(['user', 'room'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'pemohon' => $booking->user->name,
                    'tipe' => $booking->user->role,
                    'ruang' => $booking->room->nama,
                    'kegiatan' => $booking->kegiatan,
                    'waktu' => $booking->tanggal->format('d M Y') . ' ' . $booking->jam_mulai->format('H:i'),
                    'prioritas' => $booking->priority_level,
                    'jam_mulai' => $booking->jam_mulai->format('H:i'),
                    'jam_selesai' => $booking->jam_selesai->format('H:i'),
                    'tanggal' => $booking->tanggal,
                    'tujuan' => $booking->tujuan,
                    'status' => $booking->status,
                ];
            });

        $history = Booking::with(['user', 'room'])
            ->whereIn('status', ['approved', 'rejected', 'cancelled', 'completed', 'no_show'])
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'pemohon' => $booking->user->name,
                    'tipe' => $booking->user->role,
                    'ruang' => $booking->room->nama,
                    'kegiatan' => $booking->kegiatan,
                    'waktu' => $booking->tanggal->format('d M Y') . ' ' . $booking->jam_mulai->format('H:i'),
                    'status' => $booking->status,
                    'catatan' => $booking->catatan_admin ?? $booking->cancellation_reason ?? '-',
                    'diproses' => $booking->updated_at->format('d M Y, H:i'),
                    'jam_mulai' => $booking->jam_mulai->format('H:i'),
                    'jam_selesai' => $booking->jam_selesai->format('H:i'),
                    'tanggal' => $booking->tanggal,
                    'tujuan' => $booking->tujuan,
                ];
            });

        $stats = [
            'pending' => Booking::where('status', 'pending')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'rejected' => Booking::where('status', 'rejected')->count(),
            'expired' => Booking::where('status', 'no_show')->count(),
        ];

        return view('admin.bookings.approvals', compact('pending', 'history', 'stats'));
    }

    /**
     * Menyetujui booking.
     */
    public function approve(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking sudah diproses.');
        }

        $adminId = $this->currentUserId();
        $bookingService = app(BookingService::class);
        $bookingService->approve($booking, $adminId);

        return back()->with('success', "Booking {$booking->booking_code} berhasil disetujui.");
    }

    /**
     * Menolak booking dengan alasan.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking sudah diproses.');
        }

        $adminId = $this->currentUserId();
        $bookingService = app(BookingService::class);
        $bookingService->reject($booking, $request->reason, $adminId);

        return back()->with('success', "Booking {$booking->booking_code} berhasil ditolak.");
    }
}
