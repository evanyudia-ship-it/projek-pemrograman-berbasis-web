<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomManageController extends Controller
{
    // ── Data master ruang (sama persis dengan RoomController) ──────────────
    private function defaultRooms(): array
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
        ];
    }

    // ── Ambil rooms dari session (fallback ke default) ─────────────────────
    private function getRooms(): array
    {
        if (!session()->has('rooms_data')) {
            session(['rooms_data' => $this->defaultRooms()]);
        }
        return session('rooms_data');
    }

    // ── Simpan rooms ke session ────────────────────────────────────────────
    private function saveRooms(array $rooms): void
    {
        session(['rooms_data' => array_values($rooms)]);
    }

    // ── Generate ID baru ───────────────────────────────────────────────────
    private function nextId(array $rooms): int
    {
        return collect($rooms)->max('id') + 1;
    }

    // ══════════════════════════════════════════════════════════════════════
    // INDEX
    // ══════════════════════════════════════════════════════════════════════
    public function index()
    {
        $rooms         = $this->getRooms();
        $totalRooms    = count($rooms);
        $totalTersedia = collect($rooms)->where('status', 'Tersedia')->count();
        $totalDipakai  = collect($rooms)->where('status', 'Dipakai')->count();
        $totalKapasitas = collect($rooms)->sum('kapasitas');

        return view('admin.rooms.index', compact(
            'rooms',
            'totalRooms',
            'totalTersedia',
            'totalDipakai',
            'totalKapasitas'
        ));
    }

    // ══════════════════════════════════════════════════════════════════════
    // STORE (tambah ruang baru)
    // ══════════════════════════════════════════════════════════════════════
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'kode'       => 'required|string|max:20',
            'gedung'     => 'required|string|max:50',
            'lantai'     => 'required|integer|min:1',
            'kapasitas'  => 'required|integer|min:1',
            'jam_buka'   => 'required',
            'jam_tutup'  => 'required',
            'max_durasi' => 'required|string|max:30',
            'status'     => 'required|in:Tersedia,Dipakai',
            'foto'       => 'nullable|url|max:500',
            'deskripsi'  => 'nullable|string|max:500',
            'fasilitas'  => 'nullable|string',
        ]);

        $rooms = $this->getRooms();

        $fasilitas = collect(explode(',', $request->fasilitas ?? ''))
            ->map(fn($f) => trim($f))
            ->filter()
            ->values()
            ->toArray();

        $rooms[] = [
            'id'         => $this->nextId($rooms),
            'nama'       => $request->nama,
            'kode'       => strtoupper($request->kode),
            'gedung'     => $request->gedung,
            'lantai'     => (int) $request->lantai,
            'kapasitas'  => (int) $request->kapasitas,
            'jam_buka'   => $request->jam_buka,
            'jam_tutup'  => $request->jam_tutup,
            'max_durasi' => $request->max_durasi,
            'status'     => $request->status,
            'foto'       => $request->foto ?: 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&q=80',
            'deskripsi'  => $request->deskripsi ?? '',
            'fasilitas'  => $fasilitas,
            'jadwal'     => [],
        ];

        $this->saveRooms($rooms);

        return redirect()->route('admin.rooms.index')
            ->with('success', "Ruang \"{$request->nama}\" berhasil ditambahkan.");
    }

    // ══════════════════════════════════════════════════════════════════════
    // UPDATE (edit ruang)
    // ══════════════════════════════════════════════════════════════════════
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'kode'       => 'required|string|max:20',
            'gedung'     => 'required|string|max:50',
            'lantai'     => 'required|integer|min:1',
            'kapasitas'  => 'required|integer|min:1',
            'jam_buka'   => 'required',
            'jam_tutup'  => 'required',
            'max_durasi' => 'required|string|max:30',
            'status'     => 'required|in:Tersedia,Dipakai',
            'foto'       => 'nullable|url|max:500',
            'deskripsi'  => 'nullable|string|max:500',
            'fasilitas'  => 'nullable|string',
        ]);

        $rooms = $this->getRooms();
        $index = collect($rooms)->search(fn($r) => $r['id'] === $id);

        if ($index === false) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Ruang tidak ditemukan.');
        }

        $fasilitas = collect(explode(',', $request->fasilitas ?? ''))
            ->map(fn($f) => trim($f))
            ->filter()
            ->values()
            ->toArray();

        $rooms[$index] = array_merge($rooms[$index], [
            'nama'       => $request->nama,
            'kode'       => strtoupper($request->kode),
            'gedung'     => $request->gedung,
            'lantai'     => (int) $request->lantai,
            'kapasitas'  => (int) $request->kapasitas,
            'jam_buka'   => $request->jam_buka,
            'jam_tutup'  => $request->jam_tutup,
            'max_durasi' => $request->max_durasi,
            'status'     => $request->status,
            'foto'       => $request->foto ?: $rooms[$index]['foto'],
            'deskripsi'  => $request->deskripsi ?? '',
            'fasilitas'  => $fasilitas,
        ]);

        $this->saveRooms($rooms);

        return redirect()->route('admin.rooms.index')
            ->with('success', "Ruang \"{$request->nama}\" berhasil diperbarui.");
    }

    // ══════════════════════════════════════════════════════════════════════
    // TOGGLE STATUS (Tersedia ↔ Dipakai)
    // ══════════════════════════════════════════════════════════════════════
    public function toggleStatus(int $id)
    {
        $rooms = $this->getRooms();
        $index = collect($rooms)->search(fn($r) => $r['id'] === $id);

        if ($index === false) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Ruang tidak ditemukan.');
        }

        $rooms[$index]['status'] = $rooms[$index]['status'] === 'Tersedia'
            ? 'Dipakai'
            : 'Tersedia';

        $this->saveRooms($rooms);

        return redirect()->route('admin.rooms.index')
            ->with('success', "Status ruang \"{$rooms[$index]['nama']}\" berhasil diubah.");
    }

    // ══════════════════════════════════════════════════════════════════════
    // DESTROY (hapus ruang)
    // ══════════════════════════════════════════════════════════════════════
    public function destroy(int $id)
    {
        $rooms = $this->getRooms();
        $room  = collect($rooms)->firstWhere('id', $id);

        if (!$room) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Ruang tidak ditemukan.');
        }

        $rooms = collect($rooms)->reject(fn($r) => $r['id'] === $id)->toArray();
        $this->saveRooms($rooms);

        return redirect()->route('admin.rooms.index')
            ->with('success', "Ruang \"{$room['nama']}\" berhasil dihapus.");
    }

    // ══════════════════════════════════════════════════════════════════════
    // RESET (kembalikan ke data default)
    // ══════════════════════════════════════════════════════════════════════
    public function reset()
    {
        session()->forget('rooms_data');

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Data ruang berhasil direset ke default.');
    }
}