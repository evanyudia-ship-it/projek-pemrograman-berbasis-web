<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApprovalController extends Controller
{
    // Data dummy booking pending
    private array $pendingBookings = [
        [
            'id'       => 'BK-002',
            'pemohon'  => 'BEM Kampus',
            'tipe'     => 'Organisasi',
            'ruang'    => 'LAB-01',
            'kegiatan' => 'Rapat Organisasi',
            'waktu'    => '03 Mei 2026, 13.00 - 16.00',
            'prioritas'=> 'Medium',
            'status'   => 'pending',
        ],
        [
            'id'       => 'BK-004',
            'pemohon'  => 'Pak Budi',
            'tipe'     => 'Dosen',
            'ruang'    => 'R-301',
            'kegiatan' => 'Kelas Pengganti',
            'waktu'    => '04 Mei 2026, 08.00 - 11.00',
            'prioritas'=> 'High',
            'status'   => 'pending',
        ],
    ];

    // Data dummy riwayat approval
    private array $historyBookings = [
        [
            'id'        => 'BK-001',
            'pemohon'   => 'I Made Syaeful',
            'tipe'      => 'Mahasiswa',
            'ruang'     => 'R-201',
            'kegiatan'  => 'Belajar Kelompok',
            'waktu'     => '01 Mei 2026, 09.00 - 12.00',
            'prioritas' => 'Low',
            'status'    => 'approved',
            'diproses'  => '01 Mei 2026, 08.15',
            'catatan'   => '-',
        ],
        [
            'id'        => 'BK-003',
            'pemohon'   => 'Himpunan TI',
            'tipe'      => 'Organisasi',
            'ruang'     => 'Aula Utama',
            'kegiatan'  => 'Seminar Nasional',
            'waktu'     => '02 Mei 2026, 08.00 - 17.00',
            'prioritas' => 'High',
            'status'    => 'approved',
            'diproses'  => '01 Mei 2026, 20.00',
            'catatan'   => '-',
        ],
        [
            'id'        => 'BK-005',
            'pemohon'   => 'Ni Luh Ayu',
            'tipe'      => 'Mahasiswa',
            'ruang'     => 'R-105',
            'kegiatan'  => 'Rapat Kepanitiaan',
            'waktu'     => '02 Mei 2026, 13.00 - 15.00',
            'prioritas' => 'Medium',
            'status'    => 'rejected',
            'diproses'  => '01 Mei 2026, 22.30',
            'catatan'   => 'Konflik jadwal dengan kelas reguler.',
        ],
        [
            'id'        => 'BK-006',
            'pemohon'   => 'UKM Robotika',
            'tipe'      => 'Organisasi',
            'ruang'     => 'LAB-02',
            'kegiatan'  => 'Latihan Rutin',
            'waktu'     => '30 Apr 2026, 15.00 - 18.00',
            'prioritas' => 'Medium',
            'status'    => 'approved',
            'diproses'  => '29 Apr 2026, 19.00',
            'catatan'   => '-',
        ],
        [
            'id'        => 'BK-007',
            'pemohon'   => 'Pak Agus',
            'tipe'      => 'Dosen',
            'ruang'     => 'R-401',
            'kegiatan'  => 'Ujian Susulan',
            'waktu'     => '29 Apr 2026, 10.00 - 12.00',
            'prioritas' => 'High',
            'status'    => 'rejected',
            'diproses'  => '28 Apr 2026, 17.45',
            'catatan'   => 'Ruang sedang dalam perbaikan.',
        ],
    ];

    public function index()
    {
        $pending  = collect($this->pendingBookings);
        $history  = collect($this->historyBookings);

        $stats = [
            'pending'  => $pending->count(),
            'approved' => $history->where('status', 'approved')->count(),
            'rejected' => $history->where('status', 'rejected')->count(),
            'expired'  => 1, // dummy
        ];

        return view('admin.approvals', compact('pending', 'history', 'stats'));
    }

    public function approve(Request $request, $id = null)
    {
        // Nanti disambungkan ke database
        return back()->with('success', "Booking {$id} berhasil disetujui.");
    }

    public function reject(Request $request, $id = null)
    {
        $request->validate(['reason' => 'required|string|max:255']);
        // Nanti disambungkan ke database
        return back()->with('success', "Booking {$id} berhasil ditolak.");
    }
}