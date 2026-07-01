<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\AdminFaculty;
use App\Models\User;
use App\Services\BookingService;
use App\Traits\AuthenticatesUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    use AuthenticatesUser;

    /**
     * Menampilkan daftar booking pending dan riwayat approval.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Role lain tidak boleh akses
        if (!in_array($user->role, ['superadmin', 'admin'])) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Jika Admin, hanya lihat booking di fakultas yang dikelola
        $facultyIds = [];
        if ($user->role === 'admin') {
            $facultyIds = $user->adminFaculties()
                ->where('status', 'active')
                ->pluck('faculty_id')
                ->toArray();
        }

        $baseQuery = function () use ($user, $facultyIds) {
            $q = Booking::with(['user', 'room']);

            if ($user->role === 'admin') {
                $q->whereHas('room', function ($q2) use ($facultyIds) {
                    $q2->whereIn('faculty_id', $facultyIds);
                });
            }

            return $q;
        };

        // ============================================================
        // PENDING BOOKINGS - LENGKAPI DATA
        // ============================================================
        $pending = $baseQuery()->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'pemohon' => $booking->user->name,
                    'email' => $booking->user->email,
                    'fakultas' => $booking->user->faculty->name ?? '-',
                    'tipe' => $booking->user->role,
                    'ruang' => $booking->room->nama,
                    'gedung' => $booking->room->gedung ?? '-',
                    'lantai' => $booking->room->lantai ?? '-',
                    'kapasitas' => $booking->room->kapasitas ?? '-',
                    'kegiatan' => $booking->kegiatan,
                    'jenis_kegiatan' => $booking->jenis_kegiatan ? \App\Helpers\PriorityHelper::getLabel($booking->jenis_kegiatan) : '-',
                    'prioritas' => $booking->priority_level ?? '-',
                    'priority_color' => \App\Helpers\PriorityHelper::getPriorityColor($booking->priority_level ?? 'Medium'),
                    'priority_label' => \App\Helpers\PriorityHelper::getPriorityLabel($booking->priority_level ?? 'Medium'),
                    'waktu' => $booking->tanggal->format('d M Y') . ' ' . Carbon::parse($booking->jam_mulai)->format('H:i'),
                    'jam_mulai' => Carbon::parse($booking->jam_mulai)->format('H:i'),
                    'jam_selesai' => Carbon::parse($booking->jam_selesai)->format('H:i'),
                    'durasi' => $booking->durasi_menit . ' menit',
                    'tanggal' => $booking->tanggal,
                    'tujuan' => $booking->tujuan,
                    'status' => $booking->status,
                    'check_in_status' => $booking->check_in_status ?? '-',
                    'check_in_at' => $booking->check_in_at ? Carbon::parse($booking->check_in_at)->format('d M Y H:i') : '-',
                    'checkin_deadline' => $booking->checkin_deadline ? Carbon::parse($booking->checkin_deadline)->format('H:i') : '-',
                    'disetujui_oleh' => $booking->approvedBy->name ?? '-',
                    'disetujui_at' => $booking->disetujui_at ? Carbon::parse($booking->disetujui_at)->format('d M Y H:i') : '-',
                ];
            });

        // ============================================================
        // HISTORY APPROVAL - LENGKAPI DATA
        // ============================================================
        $statusFilter = $request->input('status');

        $historyQuery = $baseQuery()->whereIn('status', ['approved', 'rejected', 'cancelled', 'completed', 'no_show'])
            ->orderBy('updated_at', 'desc');

        if ($statusFilter && $statusFilter !== 'all') {
            $historyQuery->where('status', $statusFilter);
        }

        $history = $historyQuery->get()->map(function ($booking) {
            return [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'pemohon' => $booking->user->name,
                'email' => $booking->user->email,
                'fakultas' => $booking->user->faculty->name ?? '-',
                'tipe' => $booking->user->role,
                'ruang' => $booking->room->nama,
                'gedung' => $booking->room->gedung ?? '-',
                'lantai' => $booking->room->lantai ?? '-',
                'kapasitas' => $booking->room->kapasitas ?? '-',
                'kegiatan' => $booking->kegiatan,
                'jenis_kegiatan' => $booking->jenis_kegiatan ? \App\Helpers\PriorityHelper::getLabel($booking->jenis_kegiatan) : '-',
                'prioritas' => $booking->priority_level ?? '-',
                'priority_color' => \App\Helpers\PriorityHelper::getPriorityColor($booking->priority_level ?? 'Medium'),
                'priority_label' => \App\Helpers\PriorityHelper::getPriorityLabel($booking->priority_level ?? 'Medium'),
                'waktu' => $booking->tanggal->format('d M Y') . ' ' . Carbon::parse($booking->jam_mulai)->format('H:i'),
                'jam_mulai' => Carbon::parse($booking->jam_mulai)->format('H:i'),
                'jam_selesai' => Carbon::parse($booking->jam_selesai)->format('H:i'),
                'durasi' => $booking->durasi_menit . ' menit',
                'tanggal' => $booking->tanggal,
                'tujuan' => $booking->tujuan,
                'status' => $booking->status,
                'catatan' => $booking->catatan_admin ?? $booking->cancellation_reason ?? '-',
                'diproses' => $booking->updated_at->format('d M Y, H:i'),
                'check_in_status' => $booking->check_in_status ?? '-',
                'check_in_at' => $booking->check_in_at ? Carbon::parse($booking->check_in_at)->format('d M Y H:i') : '-',
                'checkin_deadline' => $booking->checkin_deadline ? Carbon::parse($booking->checkin_deadline)->format('H:i') : '-',
                'disetujui_oleh' => $booking->approvedBy->name ?? '-',
                'disetujui_at' => $booking->disetujui_at ? Carbon::parse($booking->disetujui_at)->format('d M Y H:i') : '-',
            ];
        });

        $stats = [
            'pending' => $pending->count(),
            'approved' => $baseQuery()->where('status', 'approved')->count(),
            'rejected' => $baseQuery()->where('status', 'rejected')->count(),
            'expired' => $baseQuery()->where('status', 'no_show')->count(),
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
