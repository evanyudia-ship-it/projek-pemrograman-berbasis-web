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
        // ===== FAQ =====
        $faqs = $this->getFaqs();

        // ===== ATURAN REPUTATION =====
        $aturanReputation = $this->getReputationRules();

        // ===== PANDUAN LANGKAH =====
        $panduanLangkah = $this->getStepGuide();

        // ===== DURASI BOOKING =====
        $durasiBooking = $this->getBookingDurationRules();

        // ===== KATEGORI BANTUAN =====
        $helpCategories = $this->getHelpCategories();

        // ===== ARTIKEL BANTUAN =====
        $featuredArticles = $this->getFeaturedArticles();

        // ===== KONTAK =====
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
     * Search help articles.
     */
    public function search(Request $request)
    {
        $keyword = $request->input('q');

        $articles = [];
        $faqs = [];

        if ($keyword) {
            // Search articles
            $articles = $this->searchArticles($keyword);

            // Search FAQs
            $faqs = $this->searchFaqs($keyword);
        }

        return view('help.search', compact('keyword', 'articles', 'faqs'));
    }

    /**
     * Display article detail.
     */
    public function article(string $slug)
    {
        $article = $this->getArticleBySlug($slug);

        if (!$article) {
            abort(404, 'Artikel tidak ditemukan.');
        }

        // Get related articles
        $relatedArticles = $this->getRelatedArticles($article['category'] ?? '', $slug);

        return view('help.article', compact('article', 'relatedArticles'));
    }

    /**
     * Display category articles.
     */
    public function category(string $slug)
    {
        $category = $this->getCategoryBySlug($slug);

        if (!$category) {
            abort(404, 'Kategori tidak ditemukan.');
        }

        $articles = $this->getArticlesByCategory($category['id'] ?? 0);

        return view('help.category', compact('category', 'articles'));
    }

    // ========== DATA SOURCES ==========

    /**
     * Get FAQs data.
     */
    private function getFaqs(): array
    {
        return [
            [
                'pertanyaan' => 'Bagaimana cara mengajukan booking ruang?',
                'jawaban' => 'Masuk ke menu "Ajukan Booking", pilih ruang yang tersedia, isi detail kegiatan (tanggal, jam mulai, jam selesai, jumlah peserta, dan tujuan), lalu klik "Ajukan Booking". Pengajuan akan diproses admin maksimal 1×24 jam.',
                'kategori' => 'Booking',
                'icon' => '📝',
            ],
            [
                'pertanyaan' => 'Berapa lama booking bisa diajukan sebelumnya?',
                'jawaban' => 'Booking dapat diajukan maksimal 7 hari ke depan dan minimal 1 jam sebelum waktu penggunaan. Booking mendadak kurang dari 1 jam tidak diperbolehkan.',
                'kategori' => 'Booking',
                'icon' => '⏰',
            ],
            [
                'pertanyaan' => 'Apa yang terjadi jika saya tidak check-in?',
                'jawaban' => 'Jika tidak melakukan check-in dalam 15 menit setelah jam mulai, booking otomatis berstatus "No Show" dan reputation point kamu akan berkurang 15 poin.',
                'kategori' => 'Check-in',
                'icon' => '⚠️',
            ],
            [
                'pertanyaan' => 'Bagaimana cara membatalkan booking?',
                'jawaban' => 'Buka menu "Data Booking", temukan booking yang ingin dibatalkan, lalu klik tombol "Cancel" dan isi alasan pembatalan. Pembatalan lebih dari 2 jam sebelum acara tidak akan mengurangi reputation point.',
                'kategori' => 'Booking',
                'icon' => '🔄',
            ],
            [
                'pertanyaan' => 'Mengapa booking saya ditolak?',
                'jawaban' => 'Booking bisa ditolak karena: ruang sedang dipakai di jam yang sama, jumlah peserta melebihi kapasitas ruang, reputation point di bawah batas minimum, atau durasi melebihi batas maksimum role kamu.',
                'kategori' => 'Booking',
                'icon' => '❌',
            ],
            [
                'pertanyaan' => 'Bagaimana cara meningkatkan reputation point?',
                'jawaban' => 'Reputation point bertambah dengan: check-in tepat waktu (+5), menggunakan ruang sesuai jadwal (+10), dan mengembalikan kondisi ruang dengan baik (+2). Hindari no-show, pembatalan mendadak, dan penggunaan melebihi waktu.',
                'kategori' => 'Reputasi',
                'icon' => '⭐',
            ],
            [
                'pertanyaan' => 'Apakah bisa booking lebih dari satu ruang sekaligus?',
                'jawaban' => 'Satu pengguna hanya bisa memiliki maksimal 2 booking aktif secara bersamaan. Jika sudah mencapai batas, kamu perlu menyelesaikan atau membatalkan booking yang ada sebelum mengajukan yang baru.',
                'kategori' => 'Booking',
                'icon' => '🏫',
            ],
            [
                'pertanyaan' => 'Siapa yang bisa melihat jadwal ruang?',
                'jawaban' => 'Semua pengguna yang sudah login dapat melihat jadwal dan ketersediaan ruang secara real-time melalui menu "Ketersediaan Ruang" dan "Jadwal Ruangan".',
                'kategori' => 'Umum',
                'icon' => '📅',
            ],
            [
                'pertanyaan' => 'Berapa batas waktu check-in?',
                'jawaban' => 'Batas waktu check-in adalah 15 menit setelah jam mulai booking. Jika melewati batas ini, status akan berubah menjadi "check-in terlambat" dan reputasi berkurang 5 poin.',
                'kategori' => 'Check-in',
                'icon' => '⏱️',
            ],
            [
                'pertanyaan' => 'Bagaimana cara melihat riwayat booking saya?',
                'jawaban' => 'Buka menu "Data Booking" dan kamu bisa melihat semua riwayat booking yang pernah kamu ajukan, termasuk status dan detailnya.',
                'kategori' => 'Booking',
                'icon' => '📋',
            ],
            [
                'pertanyaan' => 'Apa itu status "No Show"?',
                'jawaban' => 'Status "No Show" terjadi ketika kamu tidak melakukan check-in sama sekali sampai batas waktu berakhir. Ini akan mengurangi reputation point sebesar 15 poin dan tercatat dalam riwayat booking.',
                'kategori' => 'Check-in',
                'icon' => '🚫',
            ],
            [
                'pertanyaan' => 'Bagaimana cara menghubungi admin?',
                'jawaban' => 'Kamu dapat menghubungi admin melalui email di bagian kontak halaman ini, atau datang langsung ke ruang admin Smart Classroom di Gedung A Lantai 1.',
                'kategori' => 'Kontak',
                'icon' => '📧',
            ],
        ];
    }

    /**
     * Get reputation rules data.
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
     * Get step guide data.
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
     * Get booking duration rules.
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
                'durasi' => 'Fleksibel',
                'keterangan' => 'Butuh persetujuan penanggung jawab',
            ],
        ];
    }

    /**
     * Get help categories.
     */
    private function getHelpCategories(): array
    {
        return [
            [
                'id' => 1,
                'slug' => 'panduan-awal',
                'nama' => 'Panduan Awal',
                'icon' => '🚀',
                'description' => 'Panduan untuk pengguna baru Smart Classroom',
                'article_count' => 4,
            ],
            [
                'id' => 2,
                'slug' => 'booking',
                'nama' => 'Booking Ruang',
                'icon' => '📝',
                'description' => 'Cara mengajukan, mengelola, dan membatalkan booking',
                'article_count' => 5,
            ],
            [
                'id' => 3,
                'slug' => 'check-in',
                'nama' => 'Check-in & Presensi',
                'icon' => '✅',
                'description' => 'Prosedur check-in, batas waktu, dan konsekuensi',
                'article_count' => 3,
            ],
            [
                'id' => 4,
                'slug' => 'reputasi',
                'nama' => 'Sistem Reputasi',
                'icon' => '⭐',
                'description' => 'Cara mendapatkan dan menjaga reputation point',
                'article_count' => 3,
            ],
            [
                'id' => 5,
                'slug' => 'troubleshooting',
                'nama' => 'Troubleshooting',
                'icon' => '🔧',
                'description' => 'Solusi untuk masalah umum di Smart Classroom',
                'article_count' => 3,
            ],
        ];
    }

    /**
     * Get featured articles.
     */
    private function getFeaturedArticles(): array
    {
        return [
            [
                'slug' => 'cara-mengajukan-booking',
                'judul' => 'Cara Mengajukan Booking Ruang',
                'excerpt' => 'Panduan lengkap cara mengajukan booking ruang di Smart Classroom, mulai dari memilih ruang hingga submit booking.',
                'category' => 'Booking',
                'category_slug' => 'booking',
                'read_time' => '3 menit',
                'icon' => '📝',
                'image' => asset('images/help/booking.jpg'),
            ],
            [
                'slug' => 'panduan-check-in',
                'judul' => 'Panduan Check-in Ruang',
                'excerpt' => 'Pelajari cara melakukan check-in, batas waktu yang diberikan, dan apa yang terjadi jika Anda terlambat check-in.',
                'category' => 'Check-in',
                'category_slug' => 'check-in',
                'read_time' => '2 menit',
                'icon' => '✅',
                'image' => asset('images/help/checkin.jpg'),
            ],
            [
                'slug' => 'memahami-reputasi',
                'judul' => 'Memahami Sistem Reputasi',
                'excerpt' => 'Penjelasan lengkap tentang sistem reputation point, cara mendapatkannya, dan dampaknya terhadap kemampuan booking Anda.',
                'category' => 'Reputasi',
                'category_slug' => 'reputasi',
                'read_time' => '4 menit',
                'icon' => '⭐',
                'image' => asset('images/help/reputation.jpg'),
            ],
        ];
    }

    /**
     * Search articles.
     */
    private function searchArticles(string $keyword): array
    {
        $allArticles = $this->getFeaturedArticles();

        return array_filter($allArticles, function ($article) use ($keyword) {
            $searchable = strtolower($article['judul'] . ' ' . ($article['excerpt'] ?? '') . ' ' . ($article['category'] ?? ''));
            return strpos($searchable, strtolower($keyword)) !== false;
        });
    }

    /**
     * Search FAQs.
     */
    private function searchFaqs(string $keyword): array
    {
        $allFaqs = $this->getFaqs();

        return array_filter($allFaqs, function ($faq) use ($keyword) {
            $searchable = strtolower($faq['pertanyaan'] . ' ' . ($faq['jawaban'] ?? '') . ' ' . ($faq['kategori'] ?? ''));
            return strpos($searchable, strtolower($keyword)) !== false;
        });
    }

    /**
     * Get article by slug.
     */
    private function getArticleBySlug(string $slug): ?array
    {
        $allArticles = $this->getFeaturedArticles();

        foreach ($allArticles as $article) {
            if ($article['slug'] === $slug) {
                // Add full content
                $article['content'] = $this->getArticleContent($slug);
                return $article;
            }
        }

        return null;
    }

    /**
     * Get article content.
     */
    private function getArticleContent(string $slug): string
    {
        $contents = [
            'cara-mengajukan-booking' => '
                <h3>Langkah-langkah Mengajukan Booking</h3>
                <ol>
                    <li>Login ke akun Smart Classroom Anda.</li>
                    <li>Buka menu <strong>"Ajukan Booking"</strong> atau pilih ruang dari <strong>"Daftar Ruang"</strong>.</li>
                    <li>Pilih ruangan yang ingin Anda booking.</li>
                    <li>Isi formulir booking dengan detail:
                        <ul>
                            <li>Tanggal penggunaan</li>
                            <li>Jam mulai dan jam selesai</li>
                            <li>Kegiatan yang akan dilakukan</li>
                            <li>Tujuan penggunaan</li>
                        </ul>
                    </li>
                    <li>Periksa kembali data yang Anda isi.</li>
                    <li>Klik tombol <strong>"Ajukan Booking"</strong>.</li>
                </ol>
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl mt-4">
                    <p class="text-sm text-amber-700">
                        <strong>💡 Tips:</strong> Pastikan durasi booking tidak melebihi batas maksimum sesuai role Anda
                        (Mahasiswa: 2 jam, Dosen: 6 jam).
                    </p>
                </div>
            ',
            'panduan-check-in' => '
                <h3>Panduan Check-in</h3>
                <p>Check-in adalah proses konfirmasi kehadiran Anda di ruangan yang sudah dibooking.</p>
                <h4>Batas Waktu Check-in</h4>
                <ul>
                    <li>Anda wajib melakukan check-in <strong>maksimal 15 menit</strong> setelah jam mulai booking.</li>
                    <li>Jika check-in tepat waktu (+5 poin reputasi).</li>
                    <li>Jika check-in terlambat (-5 poin reputasi).</li>
                    <li>Jika tidak check-in sama sekali (-15 poin reputasi, status No Show).</li>
                </ul>
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl mt-4">
                    <p class="text-sm text-red-700">
                        <strong>⚠️ Penting:</strong> Status No Show akan tercatat dalam riwayat dan mempengaruhi reputasi Anda.
                    </p>
                </div>
            ',
            'memahami-reputasi' => '
                <h3>Sistem Reputasi Smart Classroom</h3>
                <p>Reputation point adalah sistem penilaian yang mencerminkan tingkat kedisiplinan dan kepatuhan Anda dalam menggunakan ruangan.</p>
                <h4>Cara Mendapatkan Poin</h4>
                <ul>
                    <li><strong>+10 poin:</strong> Menggunakan ruang sesuai jadwal</li>
                    <li><strong>+5 poin:</strong> Check-in tepat waktu</li>
                    <li><strong>+2 poin:</strong> Mengembalikan kondisi ruang dengan baik</li>
                </ul>
                <h4>Pengurangan Poin</h4>
                <ul>
                    <li><strong>-15 poin:</strong> No Show (tidak check-in)</li>
                    <li><strong>-10 poin:</strong> Membatalkan booking mendadak</li>
                    <li><strong>-5 poin:</strong> Check-in terlambat</li>
                </ul>
            ',
        ];

        return $contents[$slug] ?? '<p>Konten sedang dalam pengembangan.</p>';
    }

    /**
     * Get related articles.
     */
    private function getRelatedArticles(string $category, string $currentSlug): array
    {
        $allArticles = $this->getFeaturedArticles();

        return array_filter($allArticles, function ($article) use ($category, $currentSlug) {
            return $article['category'] === $category && $article['slug'] !== $currentSlug;
        });
    }

    /**
     * Get category by slug.
     */
    private function getCategoryBySlug(string $slug): ?array
    {
        $categories = $this->getHelpCategories();

        foreach ($categories as $category) {
            if ($category['slug'] === $slug) {
                return $category;
            }
        }

        return null;
    }

    /**
     * Get articles by category.
     */
    private function getArticlesByCategory(int $categoryId): array
    {
        $allArticles = $this->getFeaturedArticles();
        $categoryMap = [
            1 => ['Panduan Awal', 'panduan-awal'],
            2 => ['Booking', 'booking'],
            3 => ['Check-in', 'check-in'],
            4 => ['Reputasi', 'reputasi'],
            5 => ['Troubleshooting', 'troubleshooting'],
        ];

        if (!isset($categoryMap[$categoryId])) {
            return [];
        }

        $categoryName = $categoryMap[$categoryId][0];

        return array_filter($allArticles, function ($article) use ($categoryName) {
            return $article['category'] === $categoryName;
        });
    }
}
