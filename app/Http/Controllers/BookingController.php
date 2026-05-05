<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    private function getAllBookings(): array
    {
        return session('all_bookings', $this->getDummyBookings());
    }

    private function getUserBookings(): array
    {
        $userId   = session('user_id');
        $bookings = $this->getAllBookings();

        return collect($bookings)
            ->filter(fn($b) => $b['user_id'] === $userId)
            ->values()
            ->toArray();
    }

    private function getDummyBookings(): array
    {
        return [
            // Milik user_id = 3 (Dosen)
            [
                'id'         => 'BK-001',
                'user_id'    => 3,
                'pemohon'    => 'Dosen',
                'tipe'       => 'Dosen',
                'ruang'      => 'R-201',
                'ruang_nama' => 'Ruang Kelas 201',
                'kegiatan'   => 'Kelas Pengganti',
                'tanggal'    => '2026-05-03',
                'jam_mulai'  => '08:00',
                'jam_selesai'=> '10:00',
                'durasi'     => '2 jam',
                'status'     => 'approved',
                'tujuan'     => 'Kelas pengganti karena jadwal sebelumnya bentrok dengan kegiatan jurusan.',
            ],
            [
                'id'         => 'BK-007',
                'user_id'    => 3,
                'pemohon'    => 'Dosen',
                'tipe'       => 'Dosen',
                'ruang'      => 'R-301',
                'ruang_nama' => 'Ruang Kuliah 301',
                'kegiatan'   => 'Seminar Kelas',
                'tanggal'    => '2026-05-10',
                'jam_mulai'  => '13:00',
                'jam_selesai'=> '15:00',
                'durasi'     => '2 jam',
                'status'     => 'pending',
                'tujuan'     => 'Seminar kecil untuk mahasiswa tingkat akhir.',
            ],

            // Milik user_id = 4 (Mahasiswa)
            [
                'id'         => 'BK-002',
                'user_id'    => 4,
                'pemohon'    => 'I Made Syaeful Gahar',
                'tipe'       => 'Mahasiswa',
                'ruang'      => 'LAB-01',
                'ruang_nama' => 'Lab Komputer',
                'kegiatan'   => 'Rapat Organisasi',
                'tanggal'    => '2026-05-03',
                'jam_mulai'  => '13:00',
                'jam_selesai'=> '16:00',
                'durasi'     => '3 jam',
                'status'     => 'pending',
                'tujuan'     => 'Rapat rutin BEM semester genap.',
            ],
            [
                'id'         => 'BK-003',
                'user_id'    => 4,
                'pemohon'    => 'I Made Syaeful Gahar',
                'tipe'       => 'Mahasiswa',
                'ruang'      => 'R-105',
                'ruang_nama' => 'Ruang Diskusi 105',
                'kegiatan'   => 'Belajar Kelompok',
                'tanggal'    => '2026-05-02',
                'jam_mulai'  => '18:00',
                'jam_selesai'=> '20:00',
                'durasi'     => '2 jam',
                'status'     => 'completed',
                'tujuan'     => 'Belajar kelompok persiapan UAS.',
            ],
            [
                'id'         => 'BK-008',
                'user_id'    => 4,
                'pemohon'    => 'I Made Syaeful Gahar',
                'tipe'       => 'Mahasiswa',
                'ruang'      => 'R-302',
                'ruang_nama' => 'Ruang Kelas 302',
                'kegiatan'   => 'Diskusi Tugas',
                'tanggal'    => '2026-05-01',
                'jam_mulai'  => '09:00',
                'jam_selesai'=> '11:00',
                'durasi'     => '2 jam',
                'status'     => 'no_show',
                'tujuan'     => 'Diskusi pengerjaan tugas besar.',
            ],

            // Milik user_id = 1 (Admin)
            [
                'id'         => 'BK-005',
                'user_id'    => 1,
                'pemohon'    => 'Admin Kampus',
                'tipe'       => 'Admin',
                'ruang'      => 'MR-401',
                'ruang_nama' => 'Meeting Room Lt. 4',
                'kegiatan'   => 'Rapat Divisi',
                'tanggal'    => '2026-05-06',
                'jam_mulai'  => '09:00',
                'jam_selesai'=> '11:00',
                'durasi'     => '2 jam',
                'status'     => 'approved',
                'tujuan'     => 'Rapat koordinasi divisi admin.',
            ],
        ];
    }

    public function index()
    {
        $bookings   = $this->getUserBookings();
        $collection = collect($bookings);

        $stats = [
            'total'    => $collection->count(),
            'pending'  => $collection->where('status', 'pending')->count(),
            'approved' => $collection->where('status', 'approved')->count(),
            'no_show'  => $collection->where('status', 'no_show')->count(),
        ];

        return view('bookings.index', compact('bookings', 'stats'));
    }

    public function create()
    {
        return view('bookings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruang'      => 'required',
            'kegiatan'   => 'required|string|max:100',
            'tanggal'    => 'required|date|after_or_equal:today',
            'jam_mulai'  => 'required',
            'jam_selesai'=> 'required',
            'tujuan'     => 'required|string|max:500',
        ]);

        $allBookings = $this->getAllBookings();

        $newBooking = [
            'id'         => 'BK-' . str_pad(count($allBookings) + 1, 3, '0', STR_PAD_LEFT),
            'user_id'    => session('user_id'),
            'pemohon'    => session('user_name'),
            'tipe'       => ucfirst(session('user_role')),
            'ruang'      => strtoupper($request->ruang),
            'ruang_nama' => $request->ruang,
            'kegiatan'   => $request->kegiatan,
            'tanggal'    => $request->tanggal,
            'jam_mulai'  => $request->jam_mulai,
            'jam_selesai'=> $request->jam_selesai,
            'durasi'     => '-',
            'status'     => 'pending',
            'tujuan'     => $request->tujuan,
        ];

        $allBookings[] = $newBooking;
        session(['all_bookings' => $allBookings]);

        return redirect()->route('bookings.index')
                         ->with('success', 'Booking berhasil diajukan! Menunggu persetujuan admin.');
    }

    public function cancel(Request $request, string $id)
    {
        $allBookings = $this->getAllBookings();
        $userId      = session('user_id');

        $updated = collect($allBookings)->map(function ($b) use ($id, $userId) {
            if ($b['id'] === $id && $b['user_id'] === $userId && $b['status'] === 'pending') {
                $b['status'] = 'cancelled';
            }
            return $b;
        })->toArray();

        session(['all_bookings' => $updated]);

        return back()->with('success', "Booking {$id} berhasil dibatalkan.");
    }
}