<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    /**
     * Ambil data pengajuan organisasi dari session
     * Sama seperti di OrganizationController
     */
    private function getUserOrganizationSubmission(): ?array
    {
        $userId = session('user_id');
        if (!$userId) {
            return null;
        }  
        $key = "org_submissions_{$userId}";
        $submissions = session($key, []);
    
        foreach ($submissions as $submission) {
            if (in_array($submission['status'] ?? null, ['pending', 'approved', 'rejected'])) {
                $defaults = [
                    'periode' => '-',
                    'email_organisasi' => null,
                    'instagram' => null,
                    'whatsapp' => null,
                    'jenis_bukti' => [],
                    'file_bukti_nama' => null,
                    'file_bukti_path' => null,
                ];
            
                return array_merge($defaults, $submission);
            }
        }
    
        return null;
    }

    public function index()
    {
        $role = session('user_role', 'mahasiswa');
        $name = session('user_name', 'Guest');
        $email = session('user_email', '-');

        // Data dasar dari session
        $user = (object) [
            'name'  => $name,
            'email' => $email,
            'role'  => ucfirst($role),
            'nim_nip'  => session('user_nim_nip', 'Belum diisi'),
        ];

        // ========== AMBIL DATA PENGAJUAN ORGANISASI (KHUSUS MAHASISWA) ==========
        $organizationSubmission = null;
        $orgStatus = null;
        
        if ($role === 'mahasiswa') {
            $organizationSubmission = $this->getUserOrganizationSubmission();
            if ($organizationSubmission) {
                $orgStatus = $organizationSubmission['status'];
            }
        }

        // Data spesifik per role
        $profileData = match($role) {

            'superadmin' => (object) [
                'point'          => 100,
                'status'         => 'SuperAdmin',
                'status_color'   => 'purple',
                'booking_active' => 0,
                'violation'      => 0,
                'badge'          => '👑',
                'description'    => 'Akses penuh ke seluruh sistem Smart Classroom.',
                'stats' => [
                    ['label' => 'Total User',    'value' => 142,  'icon' => '👥', 'color' => 'blue'],
                    ['label' => 'Total Ruang',   'value' => 28,   'icon' => '🏫', 'color' => 'emerald'],
                    ['label' => 'Booking Aktif', 'value' => 8,    'icon' => '📅', 'color' => 'amber'],
                    ['label' => 'Pending Appr.', 'value' => 2,    'icon' => '⏳', 'color' => 'red'],
                ],
            ],

            'admin' => (object) [
                'point'          => 100,
                'status'         => 'Validator',
                'status_color'   => 'blue',
                'booking_active' => 0,
                'violation'      => 0,
                'badge'          => '✅',
                'description'    => 'Bertugas memvalidasi dan menyetujui pengajuan booking ruangan.',
                'stats' => [
                    ['label' => 'Disetujui',     'value' => 38,  'icon' => '✅', 'color' => 'emerald'],
                    ['label' => 'Ditolak',        'value' => 5,   'icon' => '❌', 'color' => 'red'],
                    ['label' => 'Pending',        'value' => 2,   'icon' => '⏳', 'color' => 'amber'],
                    ['label' => 'Total Proses',   'value' => 45,  'icon' => '📋', 'color' => 'blue'],
                ],
            ],

            'dosen' => (object) [
                'point'          => 90,
                'status'         => 'Trusted User',
                'status_color'   => 'emerald',
                'booking_active' => 2,
                'violation'      => 0,
                'badge'          => '🎓',
                'description'    => 'Dosen memiliki prioritas booking ruang kuliah dan seminar.',
                'stats' => [
                    ['label' => 'Booking Aktif',  'value' => 2,   'icon' => '📅', 'color' => 'blue'],
                    ['label' => 'Booking Selesai','value' => 24,  'icon' => '✅', 'color' => 'emerald'],
                    ['label' => 'Pelanggaran',    'value' => 0,   'icon' => '⚠️', 'color' => 'amber'],
                    ['label' => 'Reputation',     'value' => 90,  'icon' => '⭐', 'color' => 'purple'],
                ],
            ],

            // mahasiswa (default) dengan data organisasi
            default => (object) [
                'point'          => 85,
                'status'         => $orgStatus === 'approved' ? 'Perwakilan Organisasi Aktif' : 'Trusted User',
                'status_color'   => $orgStatus === 'approved' ? 'emerald' : 'emerald',
                'booking_active' => 3,
                'violation'      => 0,
                'badge'          => $orgStatus === 'approved' ? '🏢' : '🎒',
                'description'    => $orgStatus === 'approved' 
                    ? 'Anda terdaftar sebagai perwakilan resmi organisasi kampus.' 
                    : 'Mahasiswa dapat mengajukan booking ruang untuk kegiatan akademik dan organisasi.',
                'stats' => [
                    ['label' => 'Booking Aktif',  'value' => 3,   'icon' => '📅', 'color' => 'blue'],
                    ['label' => 'Booking Selesai','value' => 10,  'icon' => '✅', 'color' => 'emerald'],
                    ['label' => 'Pelanggaran',    'value' => 0,   'icon' => '⚠️', 'color' => 'amber'],
                    ['label' => 'Reputation',     'value' => 85,  'icon' => '⭐', 'color' => 'purple'],
                ],
            ],
        };

        // Aktivitas per role
        $activities = match($role) {
            'superadmin' => [
                ['icon' => '👥', 'text' => 'Menambahkan user baru: dosen@kampus.ac.id',   'waktu' => '1 jam lalu'],
                ['icon' => '🏗️', 'text' => 'Menambahkan ruang baru: LAB Komputer 3',      'waktu' => '3 jam lalu'],
                ['icon' => '⚙️', 'text' => 'Memperbarui konfigurasi sistem',              'waktu' => 'Kemarin'],
            ],
            'admin' => [
                ['icon' => '✅', 'text' => 'Menyetujui booking Ruang Seminar A',           'waktu' => '30 menit lalu'],
                ['icon' => '❌', 'text' => 'Menolak booking R-105 (konflik jadwal)',       'waktu' => '2 jam lalu'],
                ['icon' => '✅', 'text' => 'Menyetujui booking LAB Komputer 2',            'waktu' => '5 jam lalu'],
            ],
            'dosen' => [
                ['icon' => '📅', 'text' => 'Booking Ruang Seminar A dikonfirmasi',         'waktu' => '1 jam lalu'],
                ['icon' => '✅', 'text' => 'Check-in Ruang Kuliah B-12 berhasil',          'waktu' => 'Kemarin'],
                ['icon' => '➕', 'text' => 'Mengajukan booking Ruang Rapat 205',           'waktu' => '2 hari lalu'],
            ],
            default => [
                ['icon' => '✅', 'text' => 'Booking R-201 disetujui',                      'waktu' => '2 jam lalu'],
                ['icon' => '📍', 'text' => 'Check-in LAB-01 berhasil',                    'waktu' => 'Kemarin'],
                ['icon' => '➕', 'text' => 'Mengajukan booking R-105',                     'waktu' => '2 hari lalu'],
            ],
        };

        return view('profile.index', compact('user', 'profileData', 'activities', 'organizationSubmission', 'orgStatus'));
    }
}