<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    // ─────────────────────────────────────────────
    //  Middleware / Gate helper
    // ─────────────────────────────────────────────

    /**
     * Pastikan hanya role yang diizinkan yang boleh masuk.
     * Mahasiswa ✅ | Dosen ✅ | Superadmin ✅ | Admin ❌
     */
    private function authorizeAccess(): void
    {
        $role = session('user_role');

        // Admin biasa TIDAK boleh
        if (!in_array($role, ['mahasiswa', 'dosen', 'superadmin'])) {
            abort(403, 'Halaman ini hanya dapat diakses oleh Mahasiswa, Dosen, atau Superadmin.');
        }
    }

    /**
     * Cek apakah user bisa mengajukan dan membatalkan pengajuan
     * Mahasiswa ✅ | Dosen ✅ | Superadmin ❌
     */
    private function canSubmit(): bool
    {
        $role = session('user_role');
        return in_array($role, ['mahasiswa', 'dosen']);
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
     * Untuk superadmin: melihat SEMUA pengajuan dari semua user.
     * Untuk mahasiswa/dosen: melihat pengajuannya sendiri.
     */
    private function getUserSubmissions(): array
    {
        $userId = session('user_id');
        $role   = session('user_role');

        if (!$userId) {
            return [];
        }

        $key = "org_submissions_{$userId}";
        $submissions = session($key, []);

        // Superadmin melihat semua pengajuan dari semua user
        if ($role === 'superadmin' && empty($submissions)) {
            return $this->getAllSubmissionsForSuperadmin();
        }

        return $submissions;
    }

    /**
     * Superadmin melihat semua pengajuan dari semua mahasiswa & dosen
     */
    private function getAllSubmissionsForSuperadmin(): array
    {
        // Data dari berbagai user (mahasiswa dan dosen)
        $allSubmissions = [];

        // Ambil dari session user_id = 4 (Mahasiswa Budi)
        $key1 = "org_submissions_4";
        $subs1 = session($key1, []);

        // Ambil dari session user_id = 5 (Mahasiswa Citra)
        $key2 = "org_submissions_5";
        $subs2 = session($key2, []);

        // Ambil dari session user_id = 2 (Dosen)
        $key3 = "org_submissions_2";
        $subs3 = session($key3, []);

        $allSubmissions = array_merge($subs1, $subs2, $subs3);

        // Jika masih kosong, beri dummy data
        if (empty($allSubmissions)) {
            return [
                [
                    'id'                 => 'ORG-001',
                    'user_id'            => 4,
                    'user_name'          => 'Budi Santoso',
                    'user_role'          => 'mahasiswa',
                    'organisasi'         => 'Himpunan Mahasiswa Teknik Informatika',
                    'singkatan'          => 'HMTI',
                    'jabatan'            => 'Ketua Umum',
                    'deskripsi'          => 'Bertanggung jawab memimpin organisasi...',
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
                ],
                [
                    'id'                 => 'ORG-002',
                    'user_id'            => 5,
                    'user_name'          => 'Citra Dewi',
                    'user_role'          => 'mahasiswa',
                    'organisasi'         => 'BEM Universitas Undiksha',
                    'singkatan'          => 'BEM Univ',
                    'jabatan'            => 'Wakil Ketua',
                    'deskripsi'          => 'Membantu ketua dalam koordinasi...',
                    'periode'            => '2025/2026',
                    'email_organisasi'   => 'bem@undiksha.ac.id',
                    'instagram'          => '@bemundiksha',
                    'whatsapp'           => '081298765432',
                    'jenis_bukti'        => ['surat_kepengurusan'],
                    'file_bukti_nama'    => 'SK_BEM_2025.pdf',
                    'file_bukti_path'    => 'uploads/bukti_pengajuan/sk_bem.pdf',
                    'tgl_ajuan'          => '15 Mei 2025',
                    'status'             => 'pending',
                    'catatan'            => 'Menunggu verifikasi',
                ],
                [
                    'id'                 => 'ORG-003',
                    'user_id'            => 2,
                    'user_name'          => 'Dr. Ahmad Wijaya',
                    'user_role'          => 'dosen',
                    'organisasi'         => 'UKM Riset dan Inovasi',
                    'singkatan'          => 'UKRI',
                    'jabatan'            => 'Pembina',
                    'deskripsi'          => 'Sebagai pembina UKM Riset dan Inovasi...',
                    'periode'            => '2025/2026',
                    'email_organisasi'   => 'ukri@undiksha.ac.id',
                    'instagram'          => '@ukriundiksha',
                    'whatsapp'           => '082345678901',
                    'jenis_bukti'        => ['surat_rekomendasi'],
                    'file_bukti_nama'    => 'SK_Pembina_UKRI.pdf',
                    'file_bukti_path'    => 'uploads/bukti_pengajuan/sk_pembina.pdf',
                    'tgl_ajuan'          => '20 Mei 2025',
                    'status'             => 'pending',
                    'catatan'            => 'Menunggu verifikasi',
                ],
            ];
        }

        return $allSubmissions;
    }

    /**
     * Cek apakah user memiliki pengajuan aktif (pending / approved)
     * Berlaku untuk mahasiswa dan dosen
     */
    private function getActiveSubmission(array $submissions): ?array
    {
        $role = session('user_role');

        // Superadmin tidak punya konsep pengajuan aktif
        if ($role === 'superadmin') {
            return null;
        }

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
     * Halaman daftar pengajuan
     */
    public function index()
    {
        $this->authorizeAccess();

        $submissions = $this->getUserSubmissions();
        $active      = $this->getActiveSubmission($submissions);
        $role        = session('user_role');

        // Statistik
        $stats = [
            'total'    => count($submissions),
            'pending'  => count(array_filter($submissions, fn($s) => $s['status'] === 'pending')),
            'approved' => count(array_filter($submissions, fn($s) => $s['status'] === 'approved')),
            'rejected' => count(array_filter($submissions, fn($s) => $s['status'] === 'rejected')),
        ];

        return view('organisasi.index', compact('submissions', 'stats', 'active', 'role'));
    }

    /**
     * GET /organization/create
     * Halaman form pengajuan baru.
     * Mahasiswa dan Dosen bisa akses. Superadmin tidak bisa.
     */
    public function create()
    {
        $this->authorizeAccess();

        // Hanya mahasiswa dan dosen yang bisa membuat pengajuan baru
        if (!$this->canSubmit()) {
            return redirect()
                ->route('organization.index')
                ->with('info', 'Superadmin tidak dapat mengajukan perwakilan organisasi.');
        }

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
     * Mahasiswa dan Dosen bisa menyimpan.
     */
    public function store(Request $request)
    {
        $this->authorizeAccess();

        if (!$this->canSubmit()) {
            return redirect()
                ->route('organization.index')
                ->with('info', 'Superadmin tidak dapat mengajukan perwakilan organisasi.');
        }

        $submissions = $this->getUserSubmissions();
        if ($this->getActiveSubmission($submissions)) {
            return redirect()->route('organization.index')->with('info', 'Anda sudah memiliki pengajuan aktif.');
        }

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

        $file = $request->file('file_bukti');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads/bukti_pengajuan', $fileName, 'public');

        $org = collect($this->getOrganizations())->firstWhere('id', (int)$validated['organisasi_id']);

        $kode = 'ORG-' . strtoupper(substr(uniqid(), -5));

        $userId = session('user_id');
        $userName = session('user_name');
        $userRole = session('user_role');
        $key = "org_submissions_{$userId}";

        $newSubmission = [
            'id'                 => $kode,
            'user_id'            => $userId,
            'user_name'          => $userName,
            'user_role'          => $userRole,
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
     * Mahasiswa dan Dosen bisa membatalkan pengajuannya sendiri.
     */
    public function cancel(string $id)
    {
        $this->authorizeAccess();

        if (!$this->canSubmit()) {
            return redirect()
                ->route('organization.index')
                ->with('info', 'Superadmin tidak dapat membatalkan pengajuan.');
        }

        $userId = session('user_id');
        $key = "org_submissions_{$userId}";
        $all = session($key, []);

        $updated = array_values(
            array_filter($all, fn($s) => !($s['id'] === $id && $s['status'] === 'pending'))
        );

        session([$key => $updated]);

        return redirect()
            ->route('organization.index')
            ->with('success', "Pengajuan {$id} berhasil dibatalkan.");
    }
}
