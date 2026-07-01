<?php

namespace App\Http\Controllers;

use App\Models\HelpCategory;
use App\Models\HelpArticle;
use App\Models\Faq;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Display help page.
     */
    public function index()
    {
        // Get all active FAQs
        $faqs = Faq::active()->orderBy('order')->get();

        // Get reputation rules (tetap hardcode karena ini aturan sistem)
        $aturanReputation = $this->getReputationRules();

        // Get step guide (tetap hardcode)
        $panduanLangkah = $this->getStepGuide();

        // Get booking duration rules (tetap hardcode)
        $durasiBooking = $this->getBookingDurationRules();

        // Get help categories with article count
        $helpCategories = HelpCategory::withCount('articles')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // Get featured articles
        $featuredArticles = HelpArticle::featured()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Kontak (tetap hardcode)
        $campusAddress = 'Kampus Undiksha, Jalan Ahmad Yani No. 52, Singaraja, Bali';
        $adminRoomLocation = 'Ruang Admin Smart Classroom, Gedung A Lantai 1, Kampus Undiksha';
        $adminEmail = 'syaefuldarmawan02@gmail.com';
        $adminPhone = '+62 857-9727-9169';

        return view('help.index', compact(
            'faqs',
            'aturanReputation',
            'panduanLangkah',
            'durasiBooking',
            'helpCategories',
            'featuredArticles',
            'campusAddress',
            'adminRoomLocation',
            'adminEmail',
            'adminPhone'
        ));
    }

    /**
     * Search help articles and FAQs.
     */
    public function search(Request $request)
    {
        $keyword = $request->input('q');

        $articles = [];
        $faqs = [];

        if ($keyword) {
            // Search articles
            $articles = HelpArticle::active()
                ->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', "%{$keyword}%")
                        ->orWhere('content', 'LIKE', "%{$keyword}%")
                        ->orWhere('excerpt', 'LIKE', "%{$keyword}%");
                })
                ->with('category')
                ->get();

            // Search FAQs
            $faqs = Faq::active()
                ->where(function ($query) use ($keyword) {
                    $query->where('question', 'LIKE', "%{$keyword}%")
                        ->orWhere('answer', 'LIKE', "%{$keyword}%");
                })
                ->get();
        }

        return view('help.search', compact('keyword', 'articles', 'faqs'));
    }

    /**
     * Display article detail.
     */
    public function article(string $slug)
    {
        $article = HelpArticle::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment views
        $article->incrementViews();

        // Get related articles (same category)
        $relatedArticles = HelpArticle::active()
            ->where('help_category_id', $article->help_category_id)  // ← PERBAIKAN
            ->where('id', '!=', $article->id)
            ->limit(3)
            ->get();

        return view('help.article', compact('article', 'relatedArticles'));
    }

    /**
     * Display category articles.
     */
    public function category(string $slug)
    {
        $category = HelpCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $articles = HelpArticle::active()
            ->where('help_category_id', $category->id)  // ← PERBAIKAN: help_category_id
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('help.category', compact('category', 'articles'));
    }

    // ================================================================
    // 🔹 TAMBAHAN: API untuk mendapatkan data help (opsional)
    // ================================================================

    /**
     * Get popular articles (most viewed).
     */
    public function popular()
    {
        $articles = HelpArticle::active()
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        return response()->json($articles);
    }

    /**
     * Get random tips.
     */
    public function tips()
    {
        $tips = [
            'Selalu check-in tepat waktu untuk menjaga reputasi.',
            'Batalkan booking minimal 1 jam sebelum jadwal untuk menghindari penalti.',
            'Gunakan ruang sesuai dengan kapasitas yang ditentukan.',
            'Laporkan kerusakan fasilitas ke admin segera.',
            'Booking hanya untuk kepentingan akademik dan organisasi resmi.',
        ];

        return response()->json([
            'tip' => $tips[array_rand($tips)],
        ]);
    }

    // ================================================================
    // HARDCORE DATA (Tidak berubah - aturan sistem)
    // ================================================================

    /**
     * Get reputation rules data (hardcode - sistem).
     */
    private function getReputationRules(): array
    {
        return [
            [
                'range' => '80 – 100',
                'label' => 'Trusted User',
                'warna' => 'emerald',
                'emoji' => '⭐',
                'detail' => 'Proses approval lebih cepat, dapat booking hingga 3 slot aktif, prioritas antrian diutamakan.',
            ],
            [
                'range' => '50 – 79',
                'label' => 'Normal',
                'warna' => 'blue',
                'emoji' => '👤',
                'detail' => 'Dapat booking seperti biasa, maksimal 2 slot aktif, proses approval normal.',
            ],
            [
                'range' => '30 – 49',
                'label' => 'Dibatasi',
                'warna' => 'amber',
                'emoji' => '⚠️',
                'detail' => 'Booking dibatasi hanya 1 slot aktif, wajib melewati review manual admin sebelum disetujui.',
            ],
            [
                'range' => '< 30',
                'label' => 'Diblokir',
                'warna' => 'red',
                'emoji' => '🚫',
                'detail' => 'Tidak dapat mengajukan booking sementara. Hubungi admin untuk pemulihan akun.',
            ],
        ];
    }

    /**
     * Get step guide data (hardcode - sistem).
     */
    private function getStepGuide(): array
    {
        return [
            [
                'nomor' => '01',
                'judul' => 'Login ke Sistem',
                'detail' => 'Masuk menggunakan email dan password yang terdaftar. Role kamu (admin, dosen, mahasiswa, organisasi) akan terbaca otomatis.',
                'icon' => '🔑',
            ],
            [
                'nomor' => '02',
                'judul' => 'Cek Ketersediaan Ruang',
                'detail' => 'Buka menu "Ketersediaan Ruang" untuk melihat status real-time semua ruang. Gunakan filter dan pencarian untuk menemukan ruang yang sesuai.',
                'icon' => '🏫',
            ],
            [
                'nomor' => '03',
                'judul' => 'Ajukan Booking',
                'detail' => 'Isi formulir booking: pilih ruang, tanggal, jam mulai-selesai, jumlah peserta, dan tujuan kegiatan. Pastikan durasi sesuai batas role kamu.',
                'icon' => '📝',
            ],
            [
                'nomor' => '04',
                'judul' => 'Tunggu Approval',
                'detail' => 'Admin akan memproses pengajuan dalam 1×24 jam. Kamu akan mendapat notifikasi saat booking disetujui atau ditolak.',
                'icon' => '⏳',
            ],
            [
                'nomor' => '05',
                'judul' => 'Check-in Tepat Waktu',
                'detail' => 'Saat hari H, lakukan check-in melalui sistem maksimal 15 menit setelah jam mulai. Check-in tepat waktu menambah reputation point.',
                'icon' => '✅',
            ],
            [
                'nomor' => '06',
                'judul' => 'Selesai & Evaluasi',
                'detail' => 'Setelah selesai menggunakan ruang, pastikan kondisi ruang kembali seperti semula. Reputation point akan diperbarui otomatis oleh sistem.',
                'icon' => '🏆',
            ],
        ];
    }

    /**
     * Get booking duration rules (hardcode - sistem).
     */
    private function getBookingDurationRules(): array
    {
        return [
            [
                'role' => 'Mahasiswa',
                'icon' => '🎓',
                'color' => 'blue',
                'durasi' => '2 Jam',
                'keterangan' => 'Maksimal per sesi booking',
            ],
            [
                'role' => 'Dosen',
                'icon' => '👨‍🏫',
                'color' => 'violet',
                'durasi' => '6 Jam',
                'keterangan' => 'Maksimal per sesi booking',
            ],
            [
                'role' => 'Organisasi',
                'icon' => '🏢',
                'color' => 'emerald',
                'durasi' => '4 Jam',
                'keterangan' => 'Butuh persetujuan penanggung jawab',
            ],
        ];
    }
}
