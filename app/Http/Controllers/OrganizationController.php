<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    // ─────────────────────────────────────────────
    //  Middleware / Gate helper
    // ─────────────────────────────────────────────

    /**
     * Pastikan hanya role 'mahasiswa' yang boleh masuk.
     * Dipanggil di setiap method public.
     */
    private function authorizeMahasiswa(): void
    {
        $role = session('user_role');

        if ($role !== 'mahasiswa') {
            abort(403, 'Halaman ini hanya dapat diakses oleh mahasiswa.');
        }
    }

    // ─────────────────────────────────────────────
    //  Dummy data helpers
    // ─────────────────────────────────────────────

    private function getOrganizations(): array
    {
        return [
            ['id' => 1, 'nama' => 'BEM Universitas Undiksha',               'singkatan' => 'BEM Univ'],
            ['id' => 2, 'nama' => 'Himpunan Mahasiswa Teknik Informatika',   'singkatan' => 'HMTI'],
            ['id' => 3, 'nama' => 'Himpunan Mahasiswa Sistem Informasi',     'singkatan' => 'HMSI'],
            ['id' => 4, 'nama' => 'UKM Riset dan Inovasi',                  'singkatan' => 'UKRI'],
            ['id' => 5, 'nama' => 'Komunitas English Club',                  'singkatan' => 'EC'],
            ['id' => 6, 'nama' => 'UKM Olahraga Undiksha',                  'singkatan' => 'UKMO'],
            ['id' => 7, 'nama' => 'Paduan Suara Mahasiswa',                 'singkatan' => 'PSM'],
            ['id' => 8, 'nama' => 'Senat Mahasiswa Fakultas Teknik',        'singkatan' => 'SMF Teknik'],
        ];
    }

    private function getJabatan(): array
    {
        return [
            'Ketua Umum',
            'Wakil Ketua',
            'Sekretaris',
            'Bendahara',
            'Koordinator Divisi',
            'Anggota Aktif',
            'Pembina / Dosen Pendamping',
        ];
    }

    /**
     * Data dummy pengajuan milik user yang sedang login.
     * Pada implementasi nyata, ganti dengan query ke database.
     *
     * Contoh: OrganizationSubmission::where('user_id', auth()->id())->get()
     */
    private function getUserSubmissions(): array
    {
        $userId = session('user_id');
        if (!$userId) {
            return [];
        }
    
        $key = "org_submissions_{$userId}";
        $submissions = session($key, []);
    
        if ($userId === 4 && empty($submissions)) {
            $dummy = [
                'id'                 => 'ORG-001',
                'organisasi'         => 'Himpunan Mahasiswa Teknik Informatika',
                'singkatan'          => 'HMTI',
                'jabatan'            => 'Ketua Umum',
                'deskripsi'          => 'Bertanggung jawab memimpin organisasi, mengkoordinasi seluruh kegiatan HMTI, menjadi penghubung antara mahasiswa dan pihak fakultas.',
                'periode'            => '2025/2026', 
                'email_organisasi'   => 'hmtifo@undiksha.ac.id',  
                'instagram'          => '@hmtifofficial',  
                'whatsapp'           => '081234567890',  
                'jenis_bukti'        => ['surat_kepengurusan', 'sk_organisasi'],  
                'file_bukti_nama'    => 'SK_HMTI_2025.pdf',  
                'file_bukti_path'    => 'uploads/bukti_pengajuan/dummy_sk_hmtI.pdf',  
                'tgl_ajuan'          => '10 Mei 2025',
                'status'             => 'approved',
                'catatan'            => 'Dokumen lengkap, disetujui.',
            ];
            session([$key => [$dummy]]);
            return [$dummy];
        }
    
        return $submissions;
    }

    /**
     * Cek apakah user memiliki pengajuan aktif (pending / approved).
     */
    private function getActiveSubmission(array $submissions): ?array
    {
        foreach ($submissions as $sub) {
            if (in_array($sub['status'], ['pending', 'approved'])) {
                return $sub;
            }
        }
        return null;
    }

    // ─────────────────────────────────────────────
    //  Controller Actions
    // ─────────────────────────────────────────────

    /**
     * GET /organization
     * Halaman daftar pengajuan milik mahasiswa yang login.
     */
    public function index()
    {
        $this->authorizeMahasiswa();

        $submissions = $this->getUserSubmissions();
        $active      = $this->getActiveSubmission($submissions);

        $stats = [
            'total'    => count($submissions),
            'pending'  => count(array_filter($submissions, fn($s) => $s['status'] === 'pending')),
            'approved' => count(array_filter($submissions, fn($s) => $s['status'] === 'approved')),
            'rejected' => count(array_filter($submissions, fn($s) => $s['status'] === 'rejected')),
        ];

        return view('organisasi.index', compact('submissions', 'stats', 'active'));
    }

    /**
     * GET /organization/create
     * Halaman form pengajuan baru.
     * Ditolak jika mahasiswa sudah memiliki pengajuan pending / approved.
     */
    public function create()
    {
        $this->authorizeMahasiswa();

        $submissions = $this->getUserSubmissions();
        $active      = $this->getActiveSubmission($submissions);

        // Blokir jika ada pengajuan aktif
        if ($active) {
            $message = match ($active['status']) {
                'pending'  => 'Pengajuan Anda sedang menunggu persetujuan superadmin.',
                'approved' => 'Anda sudah menjadi perwakilan organisasi.',
                default    => 'Anda memiliki pengajuan aktif.',
            };

            return redirect()
                ->route('organization.index')
                ->with('info', $message);
        }

        $organizations = $this->getOrganizations();
        $jabatanList   = $this->getJabatan();

        return view('organisasi.create', compact('organizations', 'jabatanList'));
    }

    /**
     * POST /organization
     * Simpan pengajuan baru.
     */
    public function store(Request $request)
    {
        $this->authorizeMahasiswa();
    
        // Cegah double-submit
        $submissions = $this->getUserSubmissions();
        if ($this->getActiveSubmission($submissions)) {
            return redirect()->route('organization.index')->with('info', 'Anda sudah memiliki pengajuan aktif.');
        }
    
        // Validasi input + file
        $validated = $request->validate([
            'organisasi_id'      => 'required|integer|min:1',
            'jabatan'            => 'required|string|max:100',
            'deskripsi'          => 'required|string|min:20|max:1000',
            'periode'            => 'required|string|max:20|regex:/^[\d\/\-]+$/',
            'email_organisasi'   => 'nullable|email|max:100',
            'instagram'          => 'nullable|string|max:50',
            'whatsapp'           => 'nullable|string|max:20',
            'jenis_bukti'        => 'required|array|min:1',
            'jenis_bukti.*'      => 'string|in:surat_kepengurusan,sk_organisasi,surat_rekomendasi,kartu_anggota,screenshot_struktur',
            'file_bukti'         => 'required|file|mimes:pdf,jpg,png|max:2048',
        ], [
            'periode.regex'          => 'Format periode tidak valid (contoh: 2025/2026)',
            'jenis_bukti.required'   => 'Pilih minimal satu jenis bukti pendukung.',
            'file_bukti.required'    => 'File bukti pendukung wajib diunggah.',
            'file_bukti.mimes'       => 'File harus bertipe PDF, JPG, atau PNG.',
            'file_bukti.max'         => 'Ukuran file maksimal 2MB.',
        ]);
    
        // Proses upload file (dummy version: simpan di storage sementara)
        $file = $request->file('file_bukti');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads/bukti_pengajuan', $fileName, 'public'); // simpan ke storage/app/public/uploads/bukti_pengajuan
    
        // Dapatkan nama organisasi dari dummy
        $org = collect($this->getOrganizations())->firstWhere('id', (int)$validated['organisasi_id']);
    
        $kode = 'ORG-' . strtoupper(substr(uniqid(), -5));
    
        // Simpan ke session (dummy)
        $newSubmission = [
            'id'                 => $kode,
            'organisasi'         => $org['nama'] ?? 'Tidak diketahui',
            'singkatan'          => $org['singkatan'] ?? '-',
            'jabatan'            => $validated['jabatan'],
            'deskripsi'          => $validated['deskripsi'],
            'periode'            => $validated['periode'],
            'email_organisasi'   => $validated['email_organisasi'] ?? null,
            'instagram'          => $validated['instagram'] ?? null,
            'whatsapp'           => $validated['whatsapp'] ?? null,
            'jenis_bukti'        => $validated['jenis_bukti'],
            'file_bukti_nama'    => $fileName,
            'file_bukti_path'    => $filePath,
            'tgl_ajuan'          => now()->translatedFormat('d M Y'),
            'status'             => 'pending',
            'catatan'            => 'Menunggu verifikasi superadmin',
        ];
    
        $userId = session('user_id');
        $key = "org_submissions_{$userId}";
        $all = session($key, []);
        $all[] = $newSubmission;
        session([$key => $all]);
    
        return redirect()
            ->route('organization.index')
            ->with('success', "Pengajuan berhasil dikirim! Kode: {$kode}. Menunggu verifikasi superadmin.");
    }

    /**
     * DELETE /organization/{id}/cancel
     * Batalkan pengajuan yang masih berstatus pending.
     */
    public function cancel(string $id)
    {
        $this->authorizeMahasiswa();

        // ── Pada implementasi nyata ──
        // $submission = OrganizationSubmission::where('user_id', auth()->id())
        //                  ->where('id', $id)
        //                  ->where('status', 'pending')
        //                  ->firstOrFail();
        // $submission->delete();
        //
        // Dummy: hapus dari session
        $all     = $this->getUserSubmissions();
        $updated = array_values(
            array_filter($all, fn($s) => !($s['id'] === $id && $s['status'] === 'pending'))
        );
        session(['org_submissions' => $updated]);
        // ────────────────────────────

        return redirect()
            ->route('organization.index')
            ->with('success', "Pengajuan {$id} berhasil dibatalkan.");
    }
}