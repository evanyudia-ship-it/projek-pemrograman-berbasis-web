<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Booking 1: Dosen (user_id=3) booking Ruang Seminar A (room_id=1) - Approved
        Booking::create([
            'booking_code' => 'BK-00001',
            'user_id' => 3,
            'room_id' => 1,
            'kegiatan' => 'Seminar Nasional Teknologi Pendidikan',
            'tujuan' => 'Menyelenggarakan seminar nasional untuk dosen dan mahasiswa se-Bali',
            'tanggal' => Carbon::now()->addDays(5)->toDateString(),
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '16:00:00',
            'durasi_menit' => 480,
            'priority_level' => 'High',
            'status' => 'approved',
            'disetujui_oleh' => 2,
            'disetujui_at' => Carbon::now(),
            'check_in_status' => 'belum_checkin',
            'checkin_deadline' => Carbon::now()->addDays(5)->setTime(8, 30, 0),
            'is_penalty_applied' => false,
        ]);

        // Booking 2: Mahasiswa 1 (user_id=4) booking Ruang Rapat 205 (room_id=2) - Pending
        Booking::create([
            'booking_code' => 'BK-00002',
            'user_id' => 4,
            'room_id' => 2,
            'kegiatan' => 'Rapat Koordinasi HMTI',
            'tujuan' => 'Membahas persiapan acara Dies Natalis HMTI ke-15',
            'tanggal' => Carbon::now()->addDays(3)->toDateString(),
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'durasi_menit' => 120,
            'priority_level' => 'Medium',
            'status' => 'pending',
            'check_in_status' => 'belum_checkin',
            'checkin_deadline' => Carbon::now()->addDays(3)->setTime(10, 30, 0),
            'is_penalty_applied' => false,
        ]);

        // Booking 3: Mahasiswa 2 (user_id=5) booking Ruang Kuliah B-12 (room_id=3) - Approved
        Booking::create([
            'booking_code' => 'BK-00003',
            'user_id' => 5,
            'room_id' => 3,
            'kegiatan' => 'Belajar Kelompok Metode Numerik',
            'tujuan' => 'Diskusi dan pengerjaan tugas Metode Numerik bersama 5 teman',
            'tanggal' => Carbon::now()->addDays(2)->toDateString(),
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
            'durasi_menit' => 120,
            'priority_level' => 'Low',
            'status' => 'approved',
            'disetujui_oleh' => 2,
            'disetujui_at' => Carbon::now()->subHours(5),
            'check_in_status' => 'checkin_tepat_waktu',
            'check_in_at' => Carbon::now()->addDays(2)->setTime(13, 5, 0),
            'checkin_deadline' => Carbon::now()->addDays(2)->setTime(13, 30, 0),
            'is_penalty_applied' => false,
        ]);

        // Booking 4: Mahasiswa 3 (user_id=6) booking Lab Komputer (room_id=4) - Completed
        Booking::create([
            'booking_code' => 'BK-00004',
            'user_id' => 6,
            'room_id' => 4,
            'kegiatan' => 'Praktikum Pemrograman Web',
            'tujuan' => 'Praktikum membuat aplikasi web menggunakan Laravel',
            'tanggal' => Carbon::now()->subDays(2)->toDateString(),
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '12:00:00',
            'durasi_menit' => 180,
            'priority_level' => 'Medium',
            'status' => 'completed',
            'disetujui_oleh' => 2,
            'disetujui_at' => Carbon::now()->subDays(3),
            'check_in_status' => 'checkin_tepat_waktu',
            'check_in_at' => Carbon::now()->subDays(2)->setTime(8, 55, 0),
            'checkin_deadline' => Carbon::now()->subDays(2)->setTime(9, 30, 0),
            'is_penalty_applied' => false,
        ]);

        // Booking 5: Mahasiswa 4 (user_id=7) booking Aula (room_id=5) - No Show
        Booking::create([
            'booking_code' => 'BK-00005',
            'user_id' => 7,
            'room_id' => 5,
            'kegiatan' => 'Persiapan Konser Musik',
            'tujuan' => 'Latihan dan persiapan konser musik mahasiswa',
            'tanggal' => Carbon::now()->subDays(1)->toDateString(),
            'jam_mulai' => '14:00:00',
            'jam_selesai' => '18:00:00',
            'durasi_menit' => 240,
            'priority_level' => 'Medium',
            'status' => 'no_show',
            'disetujui_oleh' => 2,
            'disetujui_at' => Carbon::now()->subDays(2),
            'check_in_status' => 'no_show',
            'checkin_deadline' => Carbon::now()->subDays(1)->setTime(14, 30, 0),
            'is_penalty_applied' => true,
        ]);

        // Booking 6: Mahasiswa 1 (user_id=4) booking Lab Kimia (room_id=8) - Cancelled
        Booking::create([
            'booking_code' => 'BK-00006',
            'user_id' => 4,
            'room_id' => 8,
            'kegiatan' => 'Praktikum Kimia Dasar',
            'tujuan' => 'Praktikum titrasi asam-basa untuk mata kuliah Kimia Dasar',
            'tanggal' => Carbon::now()->addDays(1)->toDateString(),
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '10:00:00',
            'durasi_menit' => 180,
            'priority_level' => 'Medium',
            'status' => 'cancelled',
            'cancellation_reason' => 'Jadwal bentrok dengan perkuliahan',
            'cancelled_by' => 4,
            'check_in_status' => 'belum_checkin',
            'checkin_deadline' => Carbon::now()->addDays(1)->setTime(7, 30, 0),
            'is_penalty_applied' => false,
        ]);

        // Booking 7: Dosen (user_id=3) booking Ruang Studio (room_id=7) - Rejected
        Booking::create([
            'booking_code' => 'BK-00007',
            'user_id' => 3,
            'room_id' => 7,
            'kegiatan' => 'Rekaman Video Pembelajaran',
            'tujuan' => 'Merekam video pembelajaran untuk mata kuliah Desain Grafis',
            'tanggal' => Carbon::now()->addDays(4)->toDateString(),
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '12:00:00',
            'durasi_menit' => 180,
            'priority_level' => 'Medium-High',
            'status' => 'rejected',
            'catatan_admin' => 'Ruang studio sedang dalam maintenance',
            'disetujui_oleh' => 2,
            'disetujui_at' => Carbon::now()->subHours(2),
            'check_in_status' => 'belum_checkin',
            'checkin_deadline' => Carbon::now()->addDays(4)->setTime(9, 30, 0),
            'is_penalty_applied' => false,
        ]);

        // Booking 8: Mahasiswa 2 (user_id=5) booking Ruang Rapat (room_id=2) - Pending
        Booking::create([
            'booking_code' => 'BK-00008',
            'user_id' => 5,
            'room_id' => 2,
            'kegiatan' => 'Rapat Pimpinan HMTI',
            'tujuan' => 'Membahas program kerja HMTI periode 2025/2026',
            'tanggal' => Carbon::now()->addDays(7)->toDateString(),
            'jam_mulai' => '15:00:00',
            'jam_selesai' => '17:00:00',
            'durasi_menit' => 120,
            'priority_level' => 'High',
            'status' => 'pending',
            'check_in_status' => 'belum_checkin',
            'checkin_deadline' => Carbon::now()->addDays(7)->setTime(15, 30, 0),
            'is_penalty_applied' => false,
        ]);
    }
}
