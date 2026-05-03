<?php

namespace App\Http\Controllers;

class RoomController extends Controller
{
    private function getRooms(): array
    {
        return [
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
                // Jadwal: tanggal => tipe ('penuh' | 'sebagian')
                'jadwal'     => [
                    '2026-05-05' => ['label' => 'Seminar Nasional IT 2026',       'waktu' => '08:00 - 16:00', 'tipe' => 'penuh'],
                    '2026-05-08' => ['label' => 'Rapat Dosen Program Studi',      'waktu' => '09:00 - 12:00', 'tipe' => 'sebagian'],
                    '2026-05-10' => ['label' => 'Workshop UI/UX Design',          'waktu' => '13:00 - 17:00', 'tipe' => 'sebagian'],
                    '2026-05-21' => ['label' => 'Wisuda Semester Genap',          'waktu' => '07:00 - 15:00', 'tipe' => 'penuh'],
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
                'jadwal'     => [],
            ],
        ];
    }

    public function index()
    {
        $rooms = $this->getRooms();
        return view('rooms.index', compact('rooms'));
    }

    public function show(int $id)
    {
        $rooms = $this->getRooms();

        // Cari room berdasarkan id
        $room = collect($rooms)->firstWhere('id', $id);

        if (!$room) {
            abort(404, 'Ruangan tidak ditemukan.');
        }

        // Hitung total booking bulan ini
        $bulanIni  = now()->format('Y-m');
        $totalBooking = collect($room['jadwal'])
            ->filter(fn($_, $tgl) => str_starts_with($tgl, $bulanIni))
            ->count();

        // Kirim tahun & bulan kalender (default: bulan ini)
        $tahun = request('tahun', now()->year);
        $bulan = request('bulan', now()->month);

        return view('rooms.show', compact('room', 'totalBooking', 'tahun', 'bulan'));
    }
}