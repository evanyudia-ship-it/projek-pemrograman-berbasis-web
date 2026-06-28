<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Notifikasi;
use App\Models\Faculty;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Super Admin Dashboard
     */
    public function index()
    {
        // Cek role
        if (session('user_role') !== 'superadmin') {
            return $this->redirectByRole();
        }

        // ===== STATISTIK =====
        $total_ruang = Room::count();
        $ruang_tersedia = Room::available()->count();

        // Booking aktif hari ini
        $booking_aktif = Booking::whereDate('tanggal', today())
            ->where('status', Booking::STATUS_APPROVED)
            ->count();

        // Menunggu approval
        $menunggu_approval = Booking::pending()->count();

        // Reputation point user (ambil dari session user)
        $userId = session('user_id');
        $reputation_point = User::find($userId)?->reputation_points ?? 100;

        // Total users
        $total_users = User::count();
        $total_dosen = User::where('role', 'dosen')->count();
        $total_mahasiswa = User::where('role', 'mahasiswa')->count();

        // ===== JADWAL HARI INI =====
        $jadwal_hari_ini = Booking::with(['room', 'user'])
            ->whereDate('tanggal', today())
            ->whereIn('status', [Booking::STATUS_APPROVED, Booking::STATUS_PENDING])
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->room_id,
                    'ruangan' => $booking->room->nama ?? 'Ruangan',
                    'kode' => $booking->room->kode ?? '-',
                    'waktu' => Carbon::parse($booking->jam_mulai)->format('H:i') . ' - ' .
                              Carbon::parse($booking->jam_selesai)->format('H:i'),
                    'durasi' => $booking->durasi_menit . ' menit',
                    'keperluan' => $booking->kegiatan,
                    'status' => $booking->status_label,
                    'foto' => $booking->room->foto
                        ? asset('storage/' . $booking->room->foto)
                        : asset('images/default-room.jpg'),
                ];
            });

        // ===== NOTIFIKASI =====
        $notifikasi = $this->getNotifications('superadmin');

        return view('admin.SuperAdminDashboard', compact(
            'ruang_tersedia',
            'total_ruang',
            'booking_aktif',
            'menunggu_approval',
            'reputation_point',
            'total_users',
            'total_dosen',
            'total_mahasiswa',
            'jadwal_hari_ini',
            'notifikasi'
        ));
    }

    /**
     * Admin Dashboard
     */
    public function adminDashboard()
    {
        if (session('user_role') !== 'admin') {
            return $this->redirectByRole();
        }

        // ===== STATISTIK =====
        $menunggu_approval = Booking::pending()->count();

        $disetujui_hari_ini = Booking::whereDate('disetujui_at', today())
            ->where('status', Booking::STATUS_APPROVED)
            ->count();

        $ditolak_hari_ini = Booking::whereDate('updated_at', today())
            ->where('status', Booking::STATUS_REJECTED)
            ->count();

        $total_booking_aktif = Booking::where('status', Booking::STATUS_APPROVED)
            ->whereDate('tanggal', '>=', today())
            ->count();

        // ===== JADWAL HARI INI =====
        $jadwal_hari_ini = Booking::with(['room', 'user'])
            ->whereDate('tanggal', today())
            ->whereIn('status', [Booking::STATUS_APPROVED, Booking::STATUS_PENDING])
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->room_id,
                    'ruangan' => $booking->room->nama ?? 'Ruangan',
                    'kode' => $booking->room->kode ?? '-',
                    'waktu' => Carbon::parse($booking->jam_mulai)->format('H:i') . ' - ' .
                              Carbon::parse($booking->jam_selesai)->format('H:i'),
                    'durasi' => $booking->durasi_menit . ' menit',
                    'keperluan' => $booking->kegiatan,
                    'status' => $booking->status_label,
                    'foto' => $booking->room->foto
                        ? asset('storage/' . $booking->room->foto)
                        : asset('images/default-room.jpg'),
                ];
            });

        // ===== NOTIFIKASI =====
        $notifikasi = $this->getNotifications('admin');

        return view('admin.AdminDashboard', compact(
            'menunggu_approval',
            'disetujui_hari_ini',
            'ditolak_hari_ini',
            'total_booking_aktif',
            'jadwal_hari_ini',
            'notifikasi'
        ));
    }

    /**
     * User Dashboard (Mahasiswa/Dosen)
     */
    public function userDashboard()
    {
        $role = session('user_role');
        if (!in_array($role, ['mahasiswa', 'dosen'])) {
            return $this->redirectByRole();
        }

        $userId = session('user_id');

        // ===== STATISTIK =====
        // Booking aktif (approved)
        $booking_aktif = Booking::where('user_id', $userId)
            ->where('status', Booking::STATUS_APPROVED)
            ->whereDate('tanggal', '>=', today())
            ->count();

        // Booking selesai (completed)
        $booking_selesai = Booking::where('user_id', $userId)
            ->where('status', Booking::STATUS_COMPLETED)
            ->count();

        // Booking pending
        $booking_pending = Booking::where('user_id', $userId)
            ->where('status', Booking::STATUS_PENDING)
            ->count();

        // Booking no_show
        $booking_no_show = Booking::where('user_id', $userId)
            ->where('status', Booking::STATUS_NO_SHOW)
            ->count();

        // Reputation point
        $reputation_point = User::find($userId)?->reputation_points ?? 100;

        // ===== JADWAL HARI INI =====
        $jadwal_hari_ini = Booking::with(['room'])
            ->where('user_id', $userId)
            ->whereDate('tanggal', today())
            ->whereIn('status', [Booking::STATUS_APPROVED, Booking::STATUS_PENDING])
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->room_id,
                    'ruangan' => $booking->room->nama ?? 'Ruangan',
                    'kode' => $booking->room->kode ?? '-',
                    'waktu' => Carbon::parse($booking->jam_mulai)->format('H:i') . ' - ' .
                              Carbon::parse($booking->jam_selesai)->format('H:i'),
                    'durasi' => $booking->durasi_menit . ' menit',
                    'keperluan' => $booking->kegiatan,
                    'status' => $booking->status_label,
                    'foto' => $booking->room->foto
                        ? asset('storage/' . $booking->room->foto)
                        : asset('images/default-room.jpg'),
                ];
            });

        // ===== NOTIFIKASI =====
        $notifikasi = $this->getNotifications($role);

        return view('user.dashboard', compact(
            'booking_aktif',
            'booking_selesai',
            'booking_pending',
            'booking_no_show',
            'reputation_point',
            'jadwal_hari_ini',
            'notifikasi'
        ));
    }

    /**
     * Redirect by role.
     */
    private function redirectByRole()
    {
        return match(session('user_role')) {
            'superadmin' => redirect()->route('dashboard'),
            'admin'      => redirect()->route('admin.dashboard'),
            default      => redirect()->route('user.dashboard'),
        };
    }

    /**
     * Get notifications from database.
     */
    private function getNotifications(string $role): array
    {
        $userId = session('user_id');

        // Ambil notifikasi dari database
        $notifications = Notifikasi::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        if ($notifications->isNotEmpty()) {
            return $notifications->map(function ($notif) {
                return [
                    'tipe' => $notif->tipe,
                    'pesan' => $notif->pesan,
                    'waktu' => Carbon::parse($notif->created_at)->diffForHumans(),
                    'icon' => $this->getNotificationIcon($notif->tipe),
                    'read' => $notif->status === 'sudah_dibaca',
                ];
            })->toArray();
        }

        // Fallback: data dummy jika belum ada notifikasi
        return $this->dummyNotifikasi($role);
    }

    /**
     * Get notification icon by type.
     */
    private function getNotificationIcon(string $type): string
    {
        return match($type) {
            'success' => '✅',
            'warning' => '⚠️',
            'approval' => '⏳',
            'danger' => '❌',
            default => '🔔',
        };
    }

    /**
     * Dummy notifications for fallback.
     */
    private function dummyNotifikasi(string $role): array
    {
        $dummy = [
            'superadmin' => [
                [
                    'tipe' => 'info',
                    'pesan' => 'Selamat datang di dashboard Super Admin!',
                    'waktu' => 'Baru saja',
                    'icon' => '👋',
                    'read' => false,
                ],
                [
                    'tipe' => 'warning',
                    'pesan' => 'Ada ' . Booking::pending()->count() . ' booking menunggu approval',
                    'waktu' => '5 menit lalu',
                    'icon' => '⏳',
                    'read' => false,
                ],
            ],
            'admin' => [
                [
                    'tipe' => 'info',
                    'pesan' => 'Selamat datang di dashboard Admin!',
                    'waktu' => 'Baru saja',
                    'icon' => '👋',
                    'read' => false,
                ],
                [
                    'tipe' => 'warning',
                    'pesan' => 'Ada ' . Booking::pending()->count() . ' booking menunggu approval',
                    'waktu' => '5 menit lalu',
                    'icon' => '⏳',
                    'read' => false,
                ],
            ],
            'default' => [
                [
                    'tipe' => 'info',
                    'pesan' => 'Selamat datang di dashboard!',
                    'waktu' => 'Baru saja',
                    'icon' => '👋',
                    'read' => false,
                ],
                [
                    'tipe' => 'success',
                    'pesan' => 'Anda memiliki ' . Booking::where('user_id', session('user_id'))->where('status', Booking::STATUS_APPROVED)->count() . ' booking aktif',
                    'waktu' => '10 menit lalu',
                    'icon' => '✅',
                    'read' => false,
                ],
            ],
        ];

        return $dummy[$role] ?? $dummy['default'];
    }
}
