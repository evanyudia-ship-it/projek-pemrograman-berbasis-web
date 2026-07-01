<?php

namespace Database\Seeders;

use App\Models\HelpCategory;
use App\Models\HelpArticle;
use App\Models\Faq;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HelpSeeder extends Seeder
{
    public function run(): void
    {
        // ================================================================
        // PERBAIKAN: Disable foreign key check sementara
        // ================================================================
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        HelpCategory::truncate();
        HelpArticle::truncate();
        Faq::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ================================================================
        // KATEGORI
        // ================================================================
        $categories = [
            [
                'name' => 'Panduan Awal',
                'slug' => 'panduan-awal',
                'description' => 'Panduan untuk pengguna baru Smart Classroom',
                'icon' => '🚀',
                'order' => 1,
            ],
            [
                'name' => 'Booking Ruang',
                'slug' => 'booking',
                'description' => 'Cara mengajukan, mengelola, dan membatalkan booking',
                'icon' => '📝',
                'order' => 2,
            ],
            [
                'name' => 'Check-in & Presensi',
                'slug' => 'check-in',
                'description' => 'Prosedur check-in, batas waktu, dan konsekuensi',
                'icon' => '✅',
                'order' => 3,
            ],
            [
                'name' => 'Sistem Reputasi',
                'slug' => 'reputasi',
                'description' => 'Cara mendapatkan dan menjaga reputation point',
                'icon' => '⭐',
                'order' => 4,
            ],
            [
                'name' => 'Troubleshooting',
                'slug' => 'troubleshooting',
                'description' => 'Solusi untuk masalah umum di Smart Classroom',
                'icon' => '🔧',
                'order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            HelpCategory::create($category);
        }

        // ================================================================
        // ARTIKEL
        // ================================================================
        $articles = [
            [
                'help_category_id' => 1,
                'title' => 'Cara Mengajukan Booking Ruang',
                'slug' => 'cara-mengajukan-booking',
                'excerpt' => 'Panduan lengkap cara mengajukan booking ruang di Smart Classroom.',
                'content' => $this->getContent('booking'),
                'icon' => '📝',
                'read_time' => 3,
                'is_featured' => true,
            ],
            [
                'help_category_id' => 3,
                'title' => 'Panduan Check-in Ruang',
                'slug' => 'panduan-check-in',
                'excerpt' => 'Pelajari cara melakukan check-in yang benar.',
                'content' => $this->getContent('checkin'),
                'icon' => '✅',
                'read_time' => 2,
                'is_featured' => true,
            ],
            [
                'help_category_id' => 4,
                'title' => 'Memahami Sistem Reputasi',
                'slug' => 'memahami-reputasi',
                'excerpt' => 'Penjelasan lengkap tentang sistem reputation point.',
                'content' => $this->getContent('reputasi'),
                'icon' => '⭐',
                'read_time' => 4,
                'is_featured' => true,
            ],
            [
                'help_category_id' => 2,
                'title' => 'Cara Membatalkan Booking',
                'slug' => 'cara-membatalkan-booking',
                'excerpt' => 'Panduan membatalkan booking dan konsekuensinya.',
                'content' => $this->getContent('cancel'),
                'icon' => '🔄',
                'read_time' => 2,
                'is_featured' => false,
            ],
            [
                'help_category_id' => 5,
                'title' => 'Mengatasi Masalah Umum di Smart Classroom',
                'slug' => 'mengatasi-masalah-umum',
                'excerpt' => 'Solusi untuk masalah yang sering terjadi.',
                'content' => $this->getContent('troubleshoot'),
                'icon' => '🔧',
                'read_time' => 5,
                'is_featured' => false,
            ],
        ];

        foreach ($articles as $article) {
            HelpArticle::create($article);
        }

        // ================================================================
        // FAQ
        // ================================================================
        $faqs = [
            [
                'question' => 'Bagaimana cara mengajukan booking ruang?',
                'answer' => 'Masuk ke menu "Ajukan Booking", pilih ruang yang tersedia, isi detail kegiatan, lalu klik "Ajukan Booking".',
                'category' => 'Booking',
                'icon' => '📝',
                'order' => 1,
            ],
            [
                'question' => 'Apa yang terjadi jika saya tidak check-in?',
                'answer' => 'Jika tidak melakukan check-in dalam 15 menit setelah jam mulai, booking otomatis berstatus "No Show" dan reputation point berkurang 15 poin.',
                'category' => 'Check-in',
                'icon' => '⚠️',
                'order' => 3,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }

    private function getContent(string $type): string
    {
        $contents = [
            'booking' => '
                <h3>Langkah-langkah Mengajukan Booking</h3>
                <ol>
                    <li>Login ke akun Smart Classroom Anda.</li>
                    <li>Buka menu <strong>"Ajukan Booking"</strong>.</li>
                    <li>Pilih ruangan yang ingin Anda booking.</li>
                    <li>Isi formulir booking dengan detail.</li>
                    <li>Klik tombol <strong>"Ajukan Booking"</strong>.</li>
                </ol>
            ',
            'checkin' => '
                <h3>Panduan Check-in</h3>
                <p>Check-in adalah proses konfirmasi kehadiran Anda di ruangan.</p>
                <h4>Batas Waktu Check-in</h4>
                <ul>
                    <li>Wajib check-in <strong>maksimal 15 menit</strong> setelah jam mulai.</li>
                    <li>Check-in tepat waktu = +5 poin reputasi.</li>
                    <li>Check-in terlambat = -5 poin reputasi.</li>
                    <li>Tidak check-in = -15 poin reputasi (No Show).</li>
                </ul>
            ',
            'reputasi' => '
                <h3>Sistem Reputasi Smart Classroom</h3>
                <h4>Penambahan Poin</h4>
                <ul>
                    <li><strong>+10 poin:</strong> Menggunakan ruang sesuai jadwal</li>
                    <li><strong>+5 poin:</strong> Check-in tepat waktu</li>
                    <li><strong>+2 poin:</strong> Menjaga kondisi ruang</li>
                </ul>
                <h4>Pengurangan Poin</h4>
                <ul>
                    <li><strong>-15 poin:</strong> No Show</li>
                    <li><strong>-10 poin:</strong> Pembatalan mendadak</li>
                    <li><strong>-5 poin:</strong> Check-in terlambat</li>
                </ul>
            ',
            'cancel' => '
                <h3>Cara Membatalkan Booking</h3>
                <ol>
                    <li>Buka menu <strong>"Data Booking"</strong>.</li>
                    <li>Temukan booking yang ingin dibatalkan.</li>
                    <li>Klik tombol <strong>"Batalkan"</strong>.</li>
                    <li>Isi alasan pembatalan.</li>
                </ol>
                <h4>Aturan Pembatalan</h4>
                <ul>
                    <li>Pembatalan ≥ 1 jam sebelum: Tidak ada penalti</li>
                    <li>Pembatalan < 1 jam sebelum: -10 poin</li>
                </ul>
            ',
            'troubleshoot' => '
                <h3>Masalah Umum & Solusi</h3>
                <h4>1. Tidak Bisa Login</h4>
                <p>Periksa email dan password Anda. Hubungi admin jika lupa password.</p>
                <h4>2. Booking Tidak Bisa Disimpan</h4>
                <p>Pastikan semua field sudah diisi dengan benar.</p>
                <h4>3. Tidak Bisa Check-in</h4>
                <p>Pastikan booking sudah disetujui dan belum lewat batas waktu.</p>
            ',
        ];

        return $contents[$type] ?? '<p>Konten sedang dalam pengembangan.</p>';
    }
}
