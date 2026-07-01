<?php

namespace Database\Seeders;

use App\Models\HelpCategory;
use App\Models\HelpArticle;
use App\Models\Faq;
use Illuminate\Database\Seeder;

class HelpSeeder extends Seeder
{
    public function run(): void
    {
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
                'category_id' => 1,
                'title' => 'Cara Mengajukan Booking Ruang',
                'slug' => 'cara-mengajukan-booking',
                'excerpt' => 'Panduan lengkap cara mengajukan booking ruang di Smart Classroom, mulai dari memilih ruang hingga submit booking.',
                'content' => $this->getArticleContent('booking'),
                'icon' => '📝',
                'read_time' => 3,
                'is_featured' => true,
            ],
            [
                'category_id' => 3,
                'title' => 'Panduan Check-in Ruang',
                'slug' => 'panduan-check-in',
                'excerpt' => 'Pelajari cara melakukan check-in, batas waktu yang diberikan, dan apa yang terjadi jika Anda terlambat check-in.',
                'content' => $this->getArticleContent('checkin'),
                'icon' => '✅',
                'read_time' => 2,
                'is_featured' => true,
            ],
            [
                'category_id' => 4,
                'title' => 'Memahami Sistem Reputasi',
                'slug' => 'memahami-reputasi',
                'excerpt' => 'Penjelasan lengkap tentang sistem reputation point, cara mendapatkannya, dan dampaknya terhadap kemampuan booking Anda.',
                'content' => $this->getArticleContent('reputasi'),
                'icon' => '⭐',
                'read_time' => 4,
                'is_featured' => true,
            ],
            [
                'category_id' => 2,
                'title' => 'Cara Membatalkan Booking',
                'slug' => 'cara-membatalkan-booking',
                'excerpt' => 'Panduan membatalkan booking yang sudah diajukan, termasuk konsekuensi dan aturan pembatalan.',
                'content' => $this->getArticleContent('cancel'),
                'icon' => '🔄',
                'read_time' => 2,
                'is_featured' => false,
            ],
            [
                'category_id' => 5,
                'title' => 'Mengatasi Masalah Umum di Smart Classroom',
                'slug' => 'mengatasi-masalah-umum',
                'excerpt' => 'Solusi untuk masalah yang sering terjadi saat menggunakan Smart Classroom.',
                'content' => $this->getArticleContent('troubleshoot'),
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
                'answer' => 'Masuk ke menu "Ajukan Booking", pilih ruang yang tersedia, isi detail kegiatan (tanggal, jam mulai, jam selesai, jumlah peserta, dan tujuan), lalu klik "Ajukan Booking". Pengajuan akan diproses admin maksimal 1×24 jam.',
                'category' => 'Booking',
                'icon' => '📝',
                'order' => 1,
            ],
            [
                'question' => 'Berapa lama booking bisa diajukan sebelumnya?',
                'answer' => 'Booking dapat diajukan maksimal 7 hari ke depan dan minimal 1 jam sebelum waktu penggunaan. Booking mendadak kurang dari 1 jam tidak diperbolehkan.',
                'category' => 'Booking',
                'icon' => '⏰',
                'order' => 2,
            ],
            [
                'question' => 'Apa yang terjadi jika saya tidak check-in?',
                'answer' => 'Jika tidak melakukan check-in dalam 15 menit setelah jam mulai, booking otomatis berstatus "No Show" dan reputation point kamu akan berkurang 15 poin.',
                'category' => 'Check-in',
                'icon' => '⚠️',
                'order' => 3,
            ],
            [
                'question' => 'Bagaimana cara membatalkan booking?',
                'answer' => 'Buka menu "Data Booking", temukan booking yang ingin dibatalkan, lalu klik tombol "Batalkan" dan isi alasan pembatalan. Pembatalan lebih dari 1 jam sebelum acara tidak akan mengurangi reputation point.',
                'category' => 'Booking',
                'icon' => '🔄',
                'order' => 4,
            ],
            [
                'question' => 'Mengapa booking saya ditolak?',
                'answer' => 'Booking bisa ditolak karena: ruang sedang dipakai di jam yang sama, jumlah peserta melebihi kapasitas ruang, reputation point di bawah batas minimum, atau durasi melebihi batas maksimum role kamu.',
                'category' => 'Booking',
                'icon' => '❌',
                'order' => 5,
            ],
            [
                'question' => 'Bagaimana cara meningkatkan reputation point?',
                'answer' => 'Reputation point bertambah dengan: check-in tepat waktu (+5), menggunakan ruang sesuai jadwal (+10), dan mengembalikan kondisi ruang dengan baik (+2). Hindari no-show, pembatalan mendadak, dan penggunaan melebihi waktu.',
                'category' => 'Reputasi',
                'icon' => '⭐',
                'order' => 6,
            ],
            [
                'question' => 'Apakah bisa booking lebih dari satu ruang sekaligus?',
                'answer' => 'Satu pengguna hanya bisa memiliki maksimal 3 booking aktif per hari. Jika sudah mencapai batas, kamu perlu menyelesaikan atau membatalkan booking yang ada sebelum mengajukan yang baru.',
                'category' => 'Booking',
                'icon' => '🏫',
                'order' => 7,
            ],
            [
                'question' => 'Siapa yang bisa melihat jadwal ruang?',
                'answer' => 'Semua pengguna yang sudah login dapat melihat jadwal dan ketersediaan ruang secara real-time melalui menu "Ketersediaan Ruang" dan "Jadwal Ruangan".',
                'category' => 'Umum',
                'icon' => '📅',
                'order' => 8,
            ],
            [
                'question' => 'Berapa batas waktu check-in?',
                'answer' => 'Batas waktu check-in adalah 15 menit setelah jam mulai booking. Jika melewati batas ini, status akan berubah menjadi "check-in terlambat" dan reputasi berkurang 5 poin.',
                'category' => 'Check-in',
                'icon' => '⏱️',
                'order' => 9,
            ],
            [
                'question' => 'Bagaimana cara melihat riwayat booking saya?',
                'answer' => 'Buka menu "Data Booking" dan kamu bisa melihat semua riwayat booking yang pernah kamu ajukan, termasuk status dan detailnya.',
                'category' => 'Booking',
                'icon' => '📋',
                'order' => 10,
            ],
            [
                'question' => 'Apa itu status "No Show"?',
                'answer' => 'Status "No Show" terjadi ketika kamu tidak melakukan check-in sama sekali sampai batas waktu berakhir. Ini akan mengurangi reputation point sebesar 15 poin dan tercatat dalam riwayat booking.',
                'category' => 'Check-in',
                'icon' => '🚫',
                'order' => 11,
            ],
            [
                'question' => 'Bagaimana cara menghubungi admin?',
                'answer' => 'Kamu dapat menghubungi admin melalui email di bagian kontak halaman ini, atau datang langsung ke ruang admin Smart Classroom di Gedung A Lantai 1.',
                'category' => 'Kontak',
                'icon' => '📧',
                'order' => 12,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }

    private function getArticleContent(string $type): string
    {
        $contents = [
            'booking' => '
                <h3>Langkah-langkah Mengajukan Booking</h3>
                <ol>
                    <li>Login ke akun Smart Classroom Anda.</li>
                    <li>Buka menu <strong>"Ajukan Booking"</strong> atau pilih ruang dari <strong>"Daftar Ruang"</strong>.</li>
                    <li>Pilih ruangan yang ingin Anda booking.</li>
                    <li>Isi formulir booking dengan detail:
                        <ul>
                            <li>Tanggal penggunaan</li>
                            <li>Jam mulai dan jam selesai</li>
                            <li>Jumlah peserta (tidak boleh melebihi kapasitas ruang)</li>
                            <li>Jenis kegiatan (akan menentukan prioritas)</li>
                            <li>Tujuan penggunaan</li>
                        </ul>
                    </li>
                    <li>Periksa kembali data yang Anda isi.</li>
                    <li>Klik tombol <strong>"Ajukan Booking"</strong>.</li>
                </ol>
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl mt-4">
                    <p class="text-sm text-amber-700">
                        <strong>💡 Tips:</strong> Pastikan durasi booking tidak melebihi batas maksimum sesuai role Anda
                        (Mahasiswa: 2 jam, Dosen: 6 jam, Organisasi: 4 jam).
                    </p>
                </div>
            ',
            'checkin' => '
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
            'reputasi' => '
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
                    <li><strong>-10 poin:</strong> Membatalkan booking mendadak (&lt; 1 jam)</li>
                    <li><strong>-5 poin:</strong> Check-in terlambat</li>
                </ul>
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl mt-4">
                    <p class="text-sm text-blue-700">
                        <strong>📌 Level Reputasi:</strong><br>
                        • 80-100: Trusted User<br>
                        • 50-79: Normal<br>
                        • 30-49: Dibatasi<br>
                        • &lt; 30: Diblokir
                    </p>
                </div>
            ',
            'cancel' => '
                <h3>Cara Membatalkan Booking</h3>
                <ol>
                    <li>Buka menu <strong>"Data Booking"</strong>.</li>
                    <li>Temukan booking yang ingin dibatalkan.</li>
                    <li>Klik tombol <strong>"Batalkan"</strong>.</li>
                    <li>Isi alasan pembatalan dengan jelas.</li>
                    <li>Klik <strong>"Ya, Batalkan Booking"</strong>.</li>
                </ol>
                <h4>Aturan Pembatalan</h4>
                <ul>
                    <li>Pembatalan <strong>≥ 1 jam</strong> sebelum jadwal: <strong>Tidak ada penalti</strong></li>
                    <li>Pembatalan <strong>&lt; 1 jam</strong> sebelum jadwal: <strong>Pengurangan -10 poin</strong></li>
                    <li>Booking yang sudah <strong>check-in</strong> tidak dapat dibatalkan</li>
                </ul>
            ',
            'troubleshoot' => '
                <h3>Masalah Umum & Solusi</h3>
                <h4>1. Tidak Bisa Login</h4>
                <ul>
                    <li>Periksa email dan password Anda.</li>
                    <li>Pastikan akun Anda sudah terverifikasi email.</li>
                    <li>Jika lupa password, hubungi admin.</li>
                </ul>
                <h4>2. Booking Tidak Bisa Disimpan</h4>
                <ul>
                    <li>Periksa apakah semua field wajib sudah diisi.</li>
                    <li>Pastikan tanggal booking minimal hari ini.</li>
                    <li>Jam selesai harus lebih besar dari jam mulai.</li>
                    <li>Jumlah peserta tidak melebihi kapasitas ruang.</li>
                </ul>
                <h4>3. Tidak Bisa Check-in</h4>
                <ul>
                    <li>Pastikan status booking adalah "Disetujui".</li>
                    <li>Check-in hanya bisa dilakukan dalam 15 menit setelah jam mulai.</li>
                    <li>Jika sudah lewat, booking akan otomatis menjadi No Show.</li>
                </ul>
                <h4>4. Reputasi Turun Drastis</h4>
                <ul>
                    <li>Hindari no-show dengan selalu check-in tepat waktu.</li>
                    <li>Batalkan booking minimal 1 jam sebelum jadwal.</li>
                    <li>Jaga kondisi ruang saat digunakan.</li>
                </ul>
                <div class="p-4 bg-green-50 border border-green-200 rounded-xl mt-4">
                    <p class="text-sm text-green-700">
                        <strong>💡 Butuh bantuan lebih lanjut?</strong><br>
                        Hubungi admin melalui email atau datang langsung ke ruang admin.
                    </p>
                </div>
            ',
        ];

        return $contents[$type] ?? '<p>Konten sedang dalam pengembangan.</p>';
    }
}
