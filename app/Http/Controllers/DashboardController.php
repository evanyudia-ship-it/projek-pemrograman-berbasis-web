<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Notifikasi;
use App\Traits\AuthenticatesUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use AuthenticatesUser;

    public function index()
    {
        if ($this->currentRole() !== 'superadmin') {
            return $this->redirectByRole();
        }

        $userId = $this->currentUserId();

        $total_ruang = Room::count();

        $ruang_tersedia = method_exists(Room::class, 'scopeAvailable')
            ? Room::available()->count()
            : Room::whereIn('status', ['tersedia', 'available', 'aktif'])->count();

        $booking_aktif = Booking::whereDate('tanggal', today())
            ->where('status', Booking::STATUS_APPROVED)
            ->count();

        $menunggu_approval = method_exists(Booking::class, 'scopePending')
            ? Booking::pending()->count()
            : Booking::where('status', Booking::STATUS_PENDING)->count();

        $reputation_point = $this->currentUser()?->reputation_points ?? 100;

        $total_users = User::count();
        $total_dosen = User::where('role', 'dosen')->count();
        $total_mahasiswa = User::where('role', 'mahasiswa')->count();
        $total_organisasi = User::where('role', 'organisasi')->count();

        $jadwal_hari_ini = $this->getJadwalHariIni();

        // ============================================================
        // PERBAIKAN: Ambil notifikasi untuk superadmin
        // ============================================================
        $notifikasi = Notifikasi::where('user_id', $userId)
            ->where('status', 'belum_dibaca')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->select('id', 'judul', 'pesan', 'tipe', 'created_at')
            ->get()
            ->map(function ($notif) {
                $iconMap = [
                    'success' => '✅',
                    'warning' => '⚠️',
                    'approval' => '⏳',
                    'danger' => '❌',
                    'info' => 'ℹ️',
                ];
                return [
                    'icon' => $iconMap[$notif->tipe] ?? '🔔',
                    'pesan' => $notif->pesan,
                    'waktu' => $notif->created_at->diffForHumans(),
                ];
            });

        return view('admin.SuperAdminDashboard', compact(
            'ruang_tersedia',
            'total_ruang',
            'booking_aktif',
            'menunggu_approval',
            'reputation_point',
            'total_users',
            'total_dosen',
            'total_mahasiswa',
            'total_organisasi',
            'jadwal_hari_ini',
            'notifikasi'  // ← TAMBAHKAN
        ));
    }

    public function adminDashboard()
    {
        if (!in_array($this->currentRole(), ['admin', 'superadmin'])) {
            return $this->redirectByRole();
        }

        $menunggu_approval = method_exists(Booking::class, 'scopePending')
            ? Booking::pending()->count()
            : Booking::where('status', Booking::STATUS_PENDING)->count();

        $disetujui_hari_ini = Booking::whereDate('disetujui_at', today())
            ->where('status', Booking::STATUS_APPROVED)
            ->count();

        $ditolak_hari_ini = Booking::whereDate('updated_at', today())
            ->where('status', Booking::STATUS_REJECTED)
            ->count();

        $total_booking_aktif = Booking::where('status', Booking::STATUS_APPROVED)
            ->whereDate('tanggal', '>=', today())
            ->count();

        $jadwal_hari_ini = $this->getJadwalHariIni();

        return view('admin.AdminDashboard', compact(
            'menunggu_approval',
            'disetujui_hari_ini',
            'ditolak_hari_ini',
            'total_booking_aktif',
            'jadwal_hari_ini'
        ));
    }

    public function userDashboard()
    {
        $role = $this->currentRole();

        if (!in_array($role, ['mahasiswa', 'dosen', 'organisasi'])) {
            return $this->redirectByRole();
        }

        $userId = $this->currentUser()?->id ?? session('user_id');

        $booking_aktif = Booking::where('user_id', $userId)
            ->where('status', Booking::STATUS_APPROVED)
            ->whereDate('tanggal', '>=', today())
            ->count();

        $booking_selesai = Booking::where('user_id', $userId)
            ->where('status', Booking::STATUS_COMPLETED)
            ->count();

        $booking_pending = Booking::where('user_id', $userId)
            ->where('status', Booking::STATUS_PENDING)
            ->count();

        $booking_no_show = Booking::where('user_id', $userId)
            ->where('status', Booking::STATUS_NO_SHOW)
            ->count();

        $reputation_point = $this->currentUser()?->reputation_points ?? 100;

        $jadwal_hari_ini = Booking::with(['room'])
            ->where('user_id', $userId)
            ->whereDate('tanggal', today())
            ->whereIn('status', [
                Booking::STATUS_APPROVED,
                Booking::STATUS_PENDING,
            ])
            ->orderBy('jam_mulai')
            ->get()
            ->map(fn ($booking) => $this->formatBookingSchedule($booking));

        return view('user.dashboard', compact(
            'booking_aktif',
            'booking_selesai',
            'booking_pending',
            'booking_no_show',
            'reputation_point',
            'jadwal_hari_ini'
        ));
    }

    public function redirectByRole()
    {
        return match ($this->currentRole()) {
            'superadmin' => redirect()->route('dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'mahasiswa', 'dosen', 'organisasi' => redirect()->route('user.dashboard'),
            default => redirect()->route('login'),
        };
    }

    private function getJadwalHariIni()
    {
        return Booking::with(['room', 'user'])
            ->whereDate('tanggal', today())
            ->whereIn('status', [
                Booking::STATUS_APPROVED,
                Booking::STATUS_PENDING,
            ])
            ->orderBy('jam_mulai')
            ->get()
            ->map(fn ($booking) => $this->formatBookingSchedule($booking));
    }

    private function formatBookingSchedule($booking): array
    {
        return [
            'id' => $booking->room_id,
            'ruangan' => $booking->room->nama ?? $booking->room->nama_ruangan ?? 'Ruangan',
            'kode' => $booking->room->kode ?? '-',
            'waktu' => $this->formatTime($booking->jam_mulai) . ' - ' . $this->formatTime($booking->jam_selesai),
            'durasi' => ($booking->durasi_menit ?? 0) . ' menit',
            'keperluan' => $booking->kegiatan ?? '-',
            'status' => $booking->status_label ?? $booking->status,
            'foto' => !empty($booking->room?->foto)
                ? asset('storage/' . $booking->room->foto)
                : asset('images/default-room.jpg'),
        ];
    }

    private function formatTime($time): string
    {
        if (!$time) {
            return '-';
        }

        return Carbon::parse($time)->format('H:i');
    }

}
