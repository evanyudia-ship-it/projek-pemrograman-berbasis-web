<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrganizationApprovalController extends Controller
{
    /**
     * Data dummy pengajuan organisasi dari mahasiswa
     * Dalam production: OrganizationSubmission::with('user', 'organization')->get()
     */
    private array $pendingSubmissions = [
        [
            'id'                => 'ORG-001',
            'user_id'           => 4,
            'nama_mahasiswa'    => 'I Made Syaeful Anwar',
            'nim'               => '2015091001',
            'email'             => 'syaeful@undiksha.ac.id',
            'organisasi'        => 'Himpunan Mahasiswa Teknik Informatika',
            'singkatan'         => 'HMTI',
            'jabatan'           => 'Ketua Umum',
            'periode'           => '2025/2026',
            'email_organisasi'  => 'hmtifo@undiksha.ac.id',
            'instagram'         => '@hmtifofficial',
            'whatsapp'          => '081234567890',
            'deskripsi'         => 'Bertanggung jawab memimpin organisasi dalam periode 2025/2026, mengkoordinasi seluruh kegiatan HMTI, menjadi penghubung antara mahasiswa dan pihak fakultas.',
            'jenis_bukti'       => ['surat_kepengurusan', 'sk_organisasi'],
            'file_bukti_nama'   => 'SK_HMTI_2025.pdf',
            'file_bukti_path'   => 'uploads/bukti_pengajuan/1705123456_SK_HMTI.pdf',
            'tgl_ajuan'         => '10 Mei 2025',
            'status'            => 'pending',
            'catatan'           => 'Menunggu verifikasi superadmin',
        ],
        [
            'id'                => 'ORG-003',
            'user_id'           => 7,
            'nama_mahasiswa'    => 'Ni Luh Putu Dewi',
            'nim'               => '2015091023',
            'email'             => 'dewi@undiksha.ac.id',
            'organisasi'        => 'BEM Universitas Undiksha',
            'singkatan'         => 'BEM Univ',
            'jabatan'           => 'Sekretaris',
            'periode'           => '2025/2026',
            'email_organisasi'  => 'bem@undiksha.ac.id',
            'instagram'         => '@bemundiksha',
            'whatsapp'          => '089876543210',
            'deskripsi'         => 'Mengelola administrasi kesekretariatan BEM, mendokumentasikan rapat, membuat laporan pertanggungjawaban.',
            'jenis_bukti'       => ['surat_rekomendasi', 'screenshot_struktur'],
            'file_bukti_nama'   => 'SK_BEM_2025.pdf',
            'file_bukti_path'   => 'uploads/bukti_pengajuan/1705123789_SK_BEM.pdf',
            'tgl_ajuan'         => '12 Mei 2025',
            'status'            => 'pending',
            'catatan'           => 'Menunggu verifikasi superadmin',
        ],
        [
            'id'                => 'ORG-004',
            'user_id'           => 9,
            'nama_mahasiswa'    => 'Ketut Arya Wiguna',
            'nim'               => '2015091099',
            'email'             => 'arya@undiksha.ac.id',
            'organisasi'        => 'UKM Riset dan Inovasi',
            'singkatan'         => 'UKRI',
            'jabatan'           => 'Koordinator Divisi',
            'periode'           => '2024/2025',
            'email_organisasi'  => 'ukri@undiksha.ac.id',
            'instagram'         => '@ukri.undiksha',
            'whatsapp'          => '087777888999',
            'deskripsi'         => 'Mengkoordinasi divisi penelitian, membimbing anggota dalam lomba karya tulis ilmiah.',
            'jenis_bukti'       => ['kartu_anggota'],
            'file_bukti_nama'   => 'Kartu_Anggota_UKRI.jpg',
            'file_bukti_path'   => 'uploads/bukti_pengajuan/1705124000_kartu_anggota.jpg',
            'tgl_ajuan'         => '15 Mei 2025',
            'status'            => 'pending',
            'catatan'           => 'Menunggu verifikasi superadmin',
        ],
    ];

    private array $historySubmissions = [
        [
            'id'                => 'ORG-002',
            'user_id'           => 5,
            'nama_mahasiswa'    => 'Komang Satria Pratama',
            'nim'               => '2015091015',
            'email'             => 'satria@undiksha.ac.id',
            'organisasi'        => 'Paduan Suara Mahasiswa',
            'singkatan'         => 'PSM',
            'jabatan'           => 'Bendahara',
            'periode'           => '2024/2025',
            'email_organisasi'  => 'psm@undiksha.ac.id',
            'instagram'         => '@psmundiksha',
            'whatsapp'          => '081987654321',
            'deskripsi'         => 'Mengelola keuangan organisasi, membuat laporan keuangan periode 2024/2025.',
            'jenis_bukti'       => ['sk_organisasi', 'surat_kepengurusan'],
            'file_bukti_nama'   => 'SK_PSM_2024.pdf',
            'file_bukti_path'   => 'uploads/bukti_pengajuan/1705123000_SK_PSM.pdf',
            'tgl_ajuan'         => '08 Mei 2025',
            'status'            => 'approved',
            'diproses'          => '09 Mei 2025, 14.30',
            'catatan'           => 'Dokumen lengkap, disetujui.',
        ],
        [
            'id'                => 'ORG-005',
            'user_id'           => 11,
            'nama_mahasiswa'    => 'Ida Ayu Ratih',
            'nim'               => '2015091111',
            'email'             => 'ratih@undiksha.ac.id',
            'organisasi'        => 'Himpunan Mahasiswa Sistem Informasi',
            'singkatan'         => 'HMSI',
            'jabatan'           => 'Wakil Ketua',
            'periode'           => '2025/2026',
            'email_organisasi'  => 'hmsi@undiksha.ac.id',
            'instagram'         => '@hmsi_official',
            'whatsapp'          => '085556667777',
            'deskripsi'         => 'Mendampingi ketua dalam pengambilan keputusan, menggantikan ketua saat berhalangan.',
            'jenis_bukti'       => ['surat_rekomendasi'],
            'file_bukti_nama'   => 'Rekomendasi_HMSI.pdf',
            'file_bukti_path'   => 'uploads/bukti_pengajuan/1705125000_rekomendasi.pdf',
            'tgl_ajuan'         => '18 Mei 2025',
            'status'            => 'rejected',
            'diproses'          => '19 Mei 2025, 09.15',
            'catatan'           => 'Bukti kepengurusan tidak lengkap, silakan upload SK organisasi.',
        ],
        [
            'id'                => 'ORG-006',
            'user_id'           => 13,
            'nama_mahasiswa'    => 'Gede Purnama Yuda',
            'nim'               => '2015091122',
            'email'             => 'yuda@undiksha.ac.id',
            'organisasi'        => 'UKM Olahraga Undiksha',
            'singkatan'         => 'UKMO',
            'jabatan'           => 'Koordinator Divisi',
            'periode'           => '2025/2026',
            'email_organisasi'  => 'ukmo@undiksha.ac.id',
            'instagram'         => '@ukmo.undiksha',
            'whatsapp'          => '082233344455',
            'deskripsi'         => 'Mengkoordinasi kegiatan olahraga rutin dan turnamen antar fakultas.',
            'jenis_bukti'       => ['kartu_anggota', 'screenshot_struktur'],
            'file_bukti_nama'   => 'Kartu_Anggota_UKMO.png',
            'file_bukti_path'   => 'uploads/bukti_pengajuan/1705126000_kartu_ukmo.png',
            'tgl_ajuan'         => '20 Mei 2025',
            'status'            => 'approved',
            'diproses'          => '21 Mei 2025, 11.00',
            'catatan'           => 'Valid, disetujui.',
        ],
    ];

    public function index()
    {
        $pending = collect($this->pendingSubmissions);
        $history = collect($this->historySubmissions);

        $stats = [
            'pending'  => $pending->count(),
            'approved' => $history->where('status', 'approved')->count(),
            'rejected' => $history->where('status', 'rejected')->count(),
            'total'    => $pending->count() + $history->count(),
        ];

        return view('admin.organization-approvals', compact('pending', 'history', 'stats'));
    }

    public function show(string $id)
    {
        // Cari di pending dulu, baru history
        $submission = collect(array_merge($this->pendingSubmissions, $this->historySubmissions))
            ->firstWhere('id', $id);

        if (!$submission) {
            abort(404, 'Pengajuan tidak ditemukan.');
        }

        // Simulasi file URL (dalam production pakai Storage::url())
        $fileUrl = asset('storage/' . $submission['file_bukti_path']);

        return view('admin.organization-approval-detail', compact('submission', 'fileUrl'));
    }

    public function approve(string $id)
    {
        // Dummy logic: pindahkan dari pending ke history dengan status approved
        $pendingKey = array_search($id, array_column($this->pendingSubmissions, 'id'));
        
        if ($pendingKey !== false) {
            // Simulasi approve (dalam production: update database)
            return redirect()
                ->route('admin.organization-approvals.index')
                ->with('success', "Pengajuan {$id} berhasil disetujui. Mahasiswa sekarang menjadi perwakilan resmi organisasi.");
        }

        return redirect()
            ->route('admin.organization-approvals.index')
            ->with('error', "Pengajuan {$id} tidak ditemukan atau sudah diproses.");
    }

    public function reject(Request $request, string $id)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi.',
            'reason.min'      => 'Alasan penolakan minimal 10 karakter.',
        ]);

        // Dummy logic: pindahkan ke history dengan status rejected
        $pendingKey = array_search($id, array_column($this->pendingSubmissions, 'id'));
        
        if ($pendingKey !== false) {
            $reason = $request->input('reason');
            // Simulasi reject (dalam production: update database dengan catatan penolakan)
            return redirect()
                ->route('admin.organization-approvals.index')
                ->with('success', "Pengajuan {$id} berhasil ditolak. Alasan: {$reason}");
        }

        return redirect()
            ->route('admin.organization-approvals.index')
            ->with('error', "Pengajuan {$id} tidak ditemukan atau sudah diproses.");
    }

    public function downloadFile(string $id)
    {
        $submission = collect(array_merge($this->pendingSubmissions, $this->historySubmissions))
            ->firstWhere('id', $id);
    
        if (!$submission || !isset($submission['file_bukti_path'])) {
            abort(404, 'File tidak ditemukan.');
        }
    
        // Simulasi download (karena masih dummy)
        $fileName = $submission['file_bukti_nama'] ?? 'document.pdf';
        $content = "Ini adalah konten dummy dari file: " . $fileName . "\n\n";
        $content .= "Data pengajuan:\n";
        $content .= "- Organisasi: " . ($submission['organisasi'] ?? '-') . "\n";
        $content .= "- Mahasiswa: " . ($submission['nama_mahasiswa'] ?? '-') . "\n";
        
        return response($content)
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}