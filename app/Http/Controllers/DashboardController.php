<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    // SuperAdmin Dashboard (existing)
    public function index()
    {
        // Cek role
        if (session('user_role') !== 'superadmin') {
            return $this->redirectByRole();
        }

        $data = [
            'ruang_tersedia'    => 12,
            'total_ruang'       => 28,
            'booking_aktif'     => 3,
            'menunggu_approval' => 2,
            'reputation_point'  => 85,
            'total_users'       => 142,
            'total_dosen'       => 38,
            'total_mahasiswa'   => 98,
            'jadwal_hari_ini'   => $this->dummyJadwal(),
            'notifikasi'        => $this->dummyNotifikasi(),
        ];

        return view('dashboard', $data);
    }

    // Admin Dashboard
    public function adminDashboard()
    {
        if (session('user_role') !== 'admin') {
            return $this->redirectByRole();
        }

        $data = [
            'menunggu_approval' => 2,
            'disetujui_hari_ini' => 3,
            'ditolak_hari_ini'   => 1,
            'total_booking_aktif'=> 45,
            'jadwal_hari_ini'    => $this->dummyJadwal(),
            'notifikasi'         => $this->dummyNotifikasi(),
        ];

        return view('admin.dashboard', $data);
    }

    // User Dashboard (Mahasiswa & Dosen)
    public function userDashboard()
    {
        $role = session('user_role');
        if (!in_array($role, ['mahasiswa', 'dosen'])) {
            return $this->redirectByRole();
        }

        $data = [
            'booking_aktif'     => 2,
            'booking_selesai'   => 10,
            'booking_pending'   => 1,
            'reputation_point'  => 85,
            'jadwal_hari_ini'   => $this->dummyJadwal(),
            'notifikasi'        => $this->dummyNotifikasi(),
        ];

        return view('user.dashboard', $data);
    }

    private function redirectByRole()
    {
        return match(session('user_role')) {
            'superadmin' => redirect()->route('dashboard'),
            'admin'      => redirect()->route('admin.dashboard'),
            default      => redirect()->route('user.dashboard'),
        };
    }

    private function dummyJadwal(): array
    {
        return [
            [
                'id'        => 1,
                'ruangan'   => 'Ruang Seminar A - Lt. 3',
                'kode'      => 'RSA-301',
                'waktu'     => '10:00 - 12:00',
                'durasi'    => '2 jam',
                'keperluan' => 'Pemrograman Web Lanjutan',
                'status'    => 'Confirmed',
                'foto'      => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=400&q=80',
            ],
            [
                'id'        => 2,
                'ruangan'   => 'Ruang Rapat 205',
                'kode'      => 'RR-205',
                'waktu'     => '13:30 - 15:00',
                'durasi'    => '1.5 jam',
                'keperluan' => 'Rapat Organisasi Mahasiswa',
                'status'    => 'Pending',
                'foto'      => 'https://images.unsplash.com/photo-1604328698692-f76ea9498e76?w=400&q=80',
            ],
        ];
    }

    private function dummyNotifikasi(): array
    {
        return [
            [
                'tipe'  => 'approval',
                'pesan' => 'Booking Ruang Seminar A menunggu persetujuan',
                'waktu' => '2 jam yang lalu',
                'icon'  => '⏳',
            ],
            [
                'tipe'  => 'confirmed',
                'pesan' => 'Booking Ruang Kuliah B-12 telah dikonfirmasi',
                'waktu' => '5 jam yang lalu',
                'icon'  => '✅',
            ],
        ];
    }
}