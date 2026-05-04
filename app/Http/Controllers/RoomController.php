<?php

namespace App\Http\Controllers;

class RoomController extends Controller
{
    private function getRooms(): array
    {
        return session('rooms_data', [
            [
                'id'         => 1,
                'nama'       => 'Ruang Seminar A - Lt. 3',
                'kapasitas'  => 120,
                'lantai'     => 3,
                'gedung'     => 'Gedung A',
                'fasilitas'  => ['Proyektor HD', 'AC Central', 'Sound System', 'Whiteboard', 'WiFi 100Mbps', 'Podium', 'Webcam 4K'],
                'status'     => 'Tersedia',
                'kode'       => 'RSA-301',
                'jam_buka'   => '07:00',
                'jam_tutup'  => '21:00',
                'max_durasi' => '8 jam/hari',
                'foto'       => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&q=80',
                'deskripsi'  => 'Ruang seminar luas dengan panggung dan sistem audio premium. Ideal untuk seminar, kuliah umum, dan acara besar kampus.',
                'jadwal'     => [
                    '2026-05-05' => ['label' => 'Seminar Nasional IT 2026',  'waktu' => '08:00 - 16:00', 'tipe' => 'penuh'],
                    '2026-05-08' => ['label' => 'Rapat Dosen Program Studi', 'waktu' => '09:00 - 12:00', 'tipe' => 'sebagian'],
                    '2026-05-10' => ['label' => 'Workshop UI/UX Design',     'waktu' => '13:00 - 17:00', 'tipe' => 'sebagian'],
                    '2026-05-21' => ['label' => 'Wisuda Semester Genap',     'waktu' => '07:00 - 15:00', 'tipe' => 'penuh'],
                ],
            ],
            [
                'id'         => 2,
                'nama'       => 'Ruang Rapat 205',
                'kapasitas'  => 25,
                'lantai'     => 2,
                'gedung'     => 'Gedung B',
                'fasilitas'  => ['Meja Rapat', 'AC', 'TV 65"', 'WiFi', 'Whiteboard'],
                'status'     => 'Dipakai',
                'kode'       => 'RR-205',
                'jam_buka'   => '08:00',
                'jam_tutup'  => '17:00',
                'max_durasi' => '4 jam/hari',
                'foto'       => 'https://images.unsplash.com/photo-1604328698692-f76ea9498e76?w=600&q=80',
                'deskripsi'  => 'Ruang rapat eksekutif dengan meja oval dan TV layar besar. Cocok untuk rapat dosen, diskusi tim, dan pertemuan pimpinan.',
                'jadwal'     => [
                    '2026-05-03' => ['label' => 'Rapat Pimpinan', 'waktu' => '09:00 - 14:00', 'tipe' => 'penuh'],
                ],
            ],
            [
                'id'         => 3,
                'nama'       => 'Ruang Kuliah B-12',
                'kapasitas'  => 60,
                'lantai'     => 1,
                'gedung'     => 'Gedung B',
                'fasilitas'  => ['AC', 'Proyektor', 'Kursi Mahasiswa', 'Papan Tulis'],
                'status'     => 'Tersedia',
                'kode'       => 'RKB-12',
                'jam_buka'   => '07:00',
                'jam_tutup'  => '20:00',
                'max_durasi' => '6 jam/hari',
                'foto'       => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&q=80',
                'deskripsi'  => 'Ruang kuliah standar dengan kursi ergonomis dan proyektor. Lengkap untuk kegiatan belajar mengajar sehari-hari.',
                'jadwal'     => [],
            ],
            [
                'id'         => 4,
                'nama'       => 'Lab Komputer C-03',
                'kapasitas'  => 40,
                'lantai'     => 3,
                'gedung'     => 'Gedung C',
                'fasilitas'  => ['40 PC', 'AC', 'Proyektor', 'WiFi', 'UPS'],
                'status'     => 'Tersedia',
                'kode'       => 'LC-303',
                'jam_buka'   => '07:00',
                'jam_tutup'  => '20:00',
                'max_durasi' => '6 jam/hari',
                'foto'       => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=600&q=80',
                'deskripsi'  => 'Laboratorium komputer lengkap dengan 40 unit PC. Ideal untuk praktikum pemrograman, ujian online, dan workshop teknologi.',
                'jadwal'     => [],
            ],
            [
                'id'         => 5,
                'nama'       => 'Aula Utama Kampus',
                'kapasitas'  => 500,
                'lantai'     => 1,
                'gedung'     => 'Gedung Pusat',
                'fasilitas'  => ['Panggung', 'Sound System', 'AC Central', 'Kursi 500', 'Lighting'],
                'status'     => 'Dipakai',
                'kode'       => 'AUK-101',
                'jam_buka'   => '07:00',
                'jam_tutup'  => '22:00',
                'max_durasi' => '10 jam/hari',
                'foto'       => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&q=80',
                'deskripsi'  => 'Aula terbesar kampus dengan kapasitas 500 orang. Digunakan untuk wisuda, seminar besar, konser, dan acara kampus skala nasional.',
                'jadwal'     => [
                    '2026-05-03' => ['label' => 'Acara Dies Natalis', 'waktu' => '08:00 - 22:00', 'tipe' => 'penuh'],
                ],
            ],
            [
                'id'         => 6,
                'nama'       => 'Meeting Room Lt. 4',
                'kapasitas'  => 12,
                'lantai'     => 4,
                'gedung'     => 'Gedung A',
                'fasilitas'  => ['TV 55"', 'AC', 'WiFi', 'Whiteboard', 'Sofa'],
                'status'     => 'Tersedia',
                'kode'       => 'MR-401',
                'jam_buka'   => '08:00',
                'jam_tutup'  => '17:00',
                'max_durasi' => '4 jam/hari',
                'foto'       => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=600&q=80',
                'deskripsi'  => 'Ruang meeting kecil dan nyaman dengan sofa. Ideal untuk diskusi tim kecil, interview, dan coaching session.',
                'jadwal'     => [],
            ],
        ]);
    }

    public function index()
    {
        $rooms = $this->getRooms();

        // Hitung total tersedia untuk header
        $totalTersedia = collect($rooms)->where('status', 'Tersedia')->count();

        return view('rooms.index', compact('rooms', 'totalTersedia'));
    }

    public function show(int $id)
    {
        $rooms = $this->getRooms();
        $room  = collect($rooms)->firstWhere('id', $id);

        if (!$room) {
            abort(404, 'Ruangan tidak ditemukan.');
        }

        $bulanIni     = now()->format('Y-m');
        $totalBooking = collect($room['jadwal'])
            ->filter(fn($_, $tgl) => str_starts_with($tgl, $bulanIni))
            ->count();

        $tahun = request('tahun', now()->year);
        $bulan = request('bulan', now()->month);

        return view('rooms.show', compact('room', 'totalBooking', 'tahun', 'bulan'));
    }
}