<?php

namespace Database\Seeders;

use App\Models\Notifikasi;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NotificationsTableSeeder extends Seeder
{
    public function run(): void
    {
        $notifications = [
            // Untuk Super Admin (user_id=1)
            [
                'user_id' => 1,
                'judul' => 'Selamat Datang Super Admin!',
                'pesan' => 'Selamat datang di Smart Classroom. Anda memiliki akses penuh ke semua fitur.',
                'tipe' => 'info',
                'status' => 'sudah_dibaca',
                'dibaca_at' => Carbon::now(),
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => 1,
                'judul' => 'Pengguna Baru Mendaftar',
                'pesan' => 'Ada 3 pengguna baru yang mendaftar hari ini. Periksa dan verifikasi akun mereka.',
                'tipe' => 'info',
                'status' => 'belum_dibaca',
                'created_at' => Carbon::now()->subMinutes(30),
            ],

            // Untuk Admin (user_id=2)
            [
                'user_id' => 2,
                'judul' => 'Selamat Datang Admin!',
                'pesan' => 'Anda login sebagai Admin Smart Classroom. Kelola booking dan validasi pengguna.',
                'tipe' => 'info',
                'status' => 'sudah_dibaca',
                'dibaca_at' => Carbon::now(),
                'created_at' => Carbon::now()->subHours(1),
            ],
            [
                'user_id' => 2,
                'judul' => 'Booking Baru Menunggu Approval',
                'pesan' => 'Terdapat 2 booking baru yang menunggu persetujuan Anda.',
                'tipe' => 'approval',
                'status' => 'belum_dibaca',
                'entitas_terkait' => 'bookings',
                'entitas_id' => '2',
                'created_at' => Carbon::now()->subMinutes(45),
            ],
            [
                'user_id' => 2,
                'judul' => 'Booking Disetujui',
                'pesan' => 'Booking BK-00003 telah disetujui oleh Anda.',
                'tipe' => 'success',
                'status' => 'sudah_dibaca',
                'dibaca_at' => Carbon::now()->subHours(3),
                'entitas_terkait' => 'bookings',
                'entitas_id' => '3',
                'created_at' => Carbon::now()->subHours(5),
            ],

            // Untuk Dosen (user_id=3)
            [
                'user_id' => 3,
                'judul' => 'Booking Disetujui',
                'pesan' => 'Booking ruang Seminar A untuk Seminar Nasional Teknologi Pendidikan telah disetujui.',
                'tipe' => 'success',
                'status' => 'belum_dibaca',
                'entitas_terkait' => 'bookings',
                'entitas_id' => '1',
                'created_at' => Carbon::now()->subHours(4),
            ],
            [
                'user_id' => 3,
                'judul' => 'Booking Ditolak',
                'pesan' => 'Booking ruang Studio 501 untuk Rekaman Video Pembelajaran ditolak. Alasan: Ruang studio sedang dalam maintenance.',
                'tipe' => 'warning',  // <-- DIRUBAH dari 'danger' ke 'warning'
                'status' => 'belum_dibaca',
                'entitas_terkait' => 'bookings',
                'entitas_id' => '7',
                'created_at' => Carbon::now()->subHours(2),
            ],

            // Untuk Mahasiswa 1 (user_id=4)
            [
                'user_id' => 4,
                'judul' => 'Booking Menunggu Approval',
                'pesan' => 'Booking ruang Rapat 205 untuk Rapat Koordinasi HMTI menunggu persetujuan admin.',
                'tipe' => 'approval',
                'status' => 'belum_dibaca',
                'entitas_terkait' => 'bookings',
                'entitas_id' => '2',
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => 4,
                'judul' => 'Pengingat Check-in',
                'pesan' => 'Booking Lab Kimia 201 dimulai besok pukul 07:00. Jangan lupa check-in!',
                'tipe' => 'warning',
                'status' => 'belum_dibaca',
                'entitas_terkait' => 'bookings',
                'entitas_id' => '6',
                'created_at' => Carbon::now()->subMinutes(15),
            ],

            // Untuk Mahasiswa 2 (user_id=5)
            [
                'user_id' => 5,
                'judul' => 'Booking Disetujui',
                'pesan' => 'Booking ruang Kuliah B-12 untuk Belajar Kelompok Metode Numerik telah disetujui.',
                'tipe' => 'success',
                'status' => 'sudah_dibaca',
                'dibaca_at' => Carbon::now()->subHours(4),
                'entitas_terkait' => 'bookings',
                'entitas_id' => '3',
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'user_id' => 5,
                'judul' => 'Reputasi Bertambah +5',
                'pesan' => 'Poin reputasi Anda bertambah +5 karena check-in tepat waktu pada booking Ruang Kuliah B-12.',
                'tipe' => 'success',
                'status' => 'sudah_dibaca',
                'dibaca_at' => Carbon::now()->subHours(3),
                'created_at' => Carbon::now()->subHours(5),
            ],

            // Untuk Mahasiswa 3 (user_id=6)
            [
                'user_id' => 6,
                'judul' => 'Peringatan Reputasi',
                'pesan' => 'Poin reputasi Anda mencapai 45 poin. Jaga kedisiplinan penggunaan ruangan.',
                'tipe' => 'warning',
                'status' => 'belum_dibaca',
                'created_at' => Carbon::now()->subHours(1),
            ],
            [
                'user_id' => 6,
                'judul' => 'Booking Selesai',
                'pesan' => 'Booking Praktikum Pemrograman Web telah selesai. Terima kasih telah menggunakan ruangan dengan baik.',
                'tipe' => 'info',
                'status' => 'sudah_dibaca',
                'dibaca_at' => Carbon::now()->subHours(3),
                'entitas_terkait' => 'bookings',
                'entitas_id' => '4',
                'created_at' => Carbon::now()->subHours(4),
            ],

            // Untuk Mahasiswa 4 (user_id=7)
            [
                'user_id' => 7,
                'judul' => 'No Show - Booking Dibatalkan',
                'pesan' => 'Booking ruang Aula Utama dinyatakan No Show karena Anda tidak melakukan check-in. Poin reputasi berkurang -15.',
                'tipe' => 'warning',  // <-- DIRUBAH dari 'danger' ke 'warning'
                'status' => 'belum_dibaca',
                'entitas_terkait' => 'bookings',
                'entitas_id' => '5',
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'user_id' => 7,
                'judul' => 'Akun Hampir Diblokir',
                'pesan' => 'Poin reputasi Anda 30 poin. Jika terus menurun, akun Anda akan diblokir sementara.',
                'tipe' => 'warning',  // <-- DIRUBAH dari 'danger' ke 'warning'
                'status' => 'belum_dibaca',
                'created_at' => Carbon::now()->subHours(2),
            ],
        ];

        foreach ($notifications as $notif) {
            Notifikasi::create($notif);
        }
    }
}
