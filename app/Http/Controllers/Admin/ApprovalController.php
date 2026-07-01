<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\AdminFaculty;
use App\Services\BookingService;
use App\Traits\AuthenticatesUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    use AuthenticatesUser;

    /**
     * Menampilkan daftar booking pending dan riwayat approval.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Booking::with(['user', 'room']);

        // Jika SuperAdmin, lihat semua
        if ($user->role === 'superadmin') {
            // Tidak ada filter
        }
        // Jika Admin, hanya lihat booking di fakultas yang dikelola
        else if ($user->role === 'admin') {
            $facultyIds = $user->adminFaculties()
                ->where('status', 'active')
                ->pluck('faculty_id')
                ->toArray();

            $query->whereHas('room', function ($q) use ($facultyIds) {
                $q->whereIn('faculty_id', $facultyIds);
            });
        }
        // Role lain tidak boleh akses
        else {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Pending bookings
        $pending = $query->where('status', 'pending')
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

        // ============================================================
        // PERBAIKAN: Riwayat - Hapus limit(20) dan gunakan paginate atau semua data
        // ============================================================
        $history = $query->whereIn('status', ['approved', 'rejected', 'cancelled', 'completed', 'no_show'])
            ->orderBy('updated_at', 'desc')
            ->get()  // Ambil SEMUA data
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
            'pending' => $pending->count(),
            'approved' => $query->where('status', 'approved')->count(),
            'rejected' => $query->where('status', 'rejected')->count(),
            'expired' => $query->where('status', 'no_show')->count(),
        ];

        return view('admin.bookings.approvals', compact('pending', 'history', 'stats'));
    }

    /**
     * Menyetujui booking - dengan cek akses fakultas.
     */
    public function approve(Request $request, $id)
    {
        $booking = Booking::with('room')->findOrFail($id);
        $user = Auth::user();

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking sudah diproses.');
        }

        // Cek akses admin ke fakultas ruangan
        if ($user->role === 'admin') {
            $hasAccess = $user->adminFaculties()
                ->where('faculty_id', $booking->room->faculty_id)
                ->where('status', 'active')
                ->exists();

            if (!$hasAccess) {
                return back()->with('error', 'Anda tidak memiliki akses ke fakultas ruangan ini.');
            }
        }

        $adminId = $this->currentUserId();
        $bookingService = app(BookingService::class);
        $bookingService->approve($booking, $adminId);

        return back()->with('success', "Booking {$booking->booking_code} berhasil disetujui.");
    }

    /**
     * Menolak booking - dengan cek akses fakultas.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $booking = Booking::with('room')->findOrFail($id);
        $user = Auth::user();

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking sudah diproses.');
        }

        // Cek akses admin ke fakultas ruangan
        if ($user->role === 'admin') {
            $hasAccess = $user->adminFaculties()
                ->where('faculty_id', $booking->room->faculty_id)
                ->where('status', 'active')
                ->exists();

            if (!$hasAccess) {
                return back()->with('error', 'Anda tidak memiliki akses ke fakultas ruangan ini.');
            }
        }

        $adminId = $this->currentUserId();
        $bookingService = app(BookingService::class);
        $bookingService->reject($booking, $request->reason, $adminId);

        return back()->with('success', "Booking {$booking->booking_code} berhasil ditolak.");
    }
}
