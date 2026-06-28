<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomsTableSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'kode' => 'RSA-301',
                'nama' => 'Ruang Seminar A - Lt. 3',
                'gedung' => 'Gedung A',
                'lantai' => 3,
                'kapasitas' => 120,
                'alamat' => 'Kampus Tengah Undiksha, Gedung A (Rektorat), Lantai 3',
                'deskripsi' => 'Ruang seminar luas dengan panggung dan sistem audio premium. Ideal untuk seminar, kuliah umum, dan acara besar kampus.',
                'jam_buka' => '07:00:00',
                'jam_tutup' => '21:00:00',
                'max_durasi_jam' => 8,
                'status' => 'Tersedia',
                'faculty_id' => 1,
                'foto' => 'rooms/seminar-a.jpg',
            ],
            [
                'kode' => 'RR-205',
                'nama' => 'Ruang Rapat 205',
                'gedung' => 'Gedung B',
                'lantai' => 2,
                'kapasitas' => 25,
                'alamat' => 'Kampus Tengah Undiksha, Gedung B (FIP), Lantai 2',
                'deskripsi' => 'Ruang rapat eksekutif dengan meja oval dan TV layar besar. Cocok untuk rapat dosen, diskusi tim, dan pertemuan pimpinan.',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '17:00:00',
                'max_durasi_jam' => 4,
                'status' => 'Tersedia',
                'faculty_id' => 2,
                'foto' => 'rooms/rapat-205.jpg',
            ],
            [
                'kode' => 'RKB-12',
                'nama' => 'Ruang Kuliah B-12',
                'gedung' => 'Gedung B',
                'lantai' => 1,
                'kapasitas' => 60,
                'alamat' => 'Kampus Tengah Undiksha, Gedung B (FIP), Lantai 1',
                'deskripsi' => 'Ruang kuliah standar dengan kursi ergonomis dan proyektor. Lengkap untuk kegiatan belajar mengajar sehari-hari.',
                'jam_buka' => '07:00:00',
                'jam_tutup' => '20:00:00',
                'max_durasi_jam' => 6,
                'status' => 'Tersedia',
                'faculty_id' => 2,
                'foto' => 'rooms/kuliah-b12.jpg',
            ],
            [
                'kode' => 'LC-303',
                'nama' => 'Lab Komputer C-03',
                'gedung' => 'Gedung C',
                'lantai' => 3,
                'kapasitas' => 40,
                'alamat' => 'Kampus Tengah Undiksha, Gedung C (FTK), Lantai 3',
                'deskripsi' => 'Laboratorium komputer lengkap dengan 40 unit PC. Ideal untuk praktikum pemrograman, ujian online, dan workshop teknologi.',
                'jam_buka' => '07:00:00',
                'jam_tutup' => '20:00:00',
                'max_durasi_jam' => 6,
                'status' => 'Tersedia',
                'faculty_id' => 1,
                'foto' => 'rooms/lab-c03.jpg',
            ],
            [
                'kode' => 'AUK-101',
                'nama' => 'Aula Utama Kampus',
                'gedung' => 'Gedung Pusat',
                'lantai' => 1,
                'kapasitas' => 500,
                'alamat' => 'Kampus Tengah Undiksha, Gedung Pusat, Lantai 1',
                'deskripsi' => 'Aula terbesar kampus dengan kapasitas 500 orang. Digunakan untuk wisuda, seminar besar, konser, dan acara kampus skala nasional.',
                'jam_buka' => '07:00:00',
                'jam_tutup' => '22:00:00',
                'max_durasi_jam' => 10,
                'status' => 'Maintenance',
                'faculty_id' => null,
                'foto' => 'rooms/aula-utama.jpg',
            ],
            [
                'kode' => 'MR-401',
                'nama' => 'Meeting Room Lt. 4',
                'gedung' => 'Gedung A',
                'lantai' => 4,
                'kapasitas' => 12,
                'alamat' => 'Kampus Tengah Undiksha, Gedung A (Rektorat), Lantai 4',
                'deskripsi' => 'Ruang meeting kecil dan nyaman dengan sofa. Ideal untuk diskusi tim kecil, interview, dan coaching session.',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '17:00:00',
                'max_durasi_jam' => 4,
                'status' => 'Tersedia',
                'faculty_id' => null,
                'foto' => 'rooms/meeting-401.jpg',
            ],
            [
                'kode' => 'RS-501',
                'nama' => 'Ruang Studio 501',
                'gedung' => 'Gedung E',
                'lantai' => 5,
                'kapasitas' => 30,
                'alamat' => 'Kampus Tengah Undiksha, Gedung E (Auditorium), Lantai 5',
                'deskripsi' => 'Ruang studio multimedia untuk rekaman, shooting video, dan produksi konten.',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '18:00:00',
                'max_durasi_jam' => 4,
                'status' => 'Tersedia',
                'faculty_id' => 3,
                'foto' => 'rooms/studio-501.jpg',
            ],
            [
                'kode' => 'LK-201',
                'nama' => 'Lab Kimia 201',
                'gedung' => 'Gedung D',
                'lantai' => 2,
                'kapasitas' => 35,
                'alamat' => 'Kampus Tengah Undiksha, Gedung D (FMIPA), Lantai 2',
                'deskripsi' => 'Laboratorium kimia lengkap dengan alat praktikum dan bahan kimia untuk praktikum mahasiswa.',
                'jam_buka' => '07:00:00',
                'jam_tutup' => '18:00:00',
                'max_durasi_jam' => 4,
                'status' => 'Tersedia',
                'faculty_id' => 4,
                'foto' => 'rooms/lab-kimia.jpg',
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
