<?php

namespace App\Http\Controllers;

class HelpController extends Controller
{
    public function index()
    {
        $faqs = [
            [
                'pertanyaan' => 'Bagaimana cara mengajukan booking ruang?',
                'jawaban'    => 'Masuk ke menu "Ajukan Booking", pilih ruang yang tersedia, isi detail kegiatan (tanggal, jam mulai, jam selesai, jumlah peserta, dan tujuan), lalu klik "Ajukan Booking". Pengajuan akan diproses admin maksimal 1×24 jam.',
            ],
            [
                'pertanyaan' => 'Berapa lama booking bisa diajukan sebelumnya?',
                'jawaban'    => 'Booking dapat diajukan maksimal 7 hari ke depan dan minimal 1 jam sebelum waktu penggunaan. Booking mendadak kurang dari 1 jam tidak diperbolehkan.',
            ],
            [
                'pertanyaan' => 'Apa yang terjadi jika saya tidak check-in?',
                'jawaban'    => 'Jika tidak melakukan check-in dalam 15 menit setelah jam mulai, booking otomatis berstatus "No Show" dan reputation point kamu akan berkurang 10 poin.',
            ],
            [
                'pertanyaan' => 'Bagaimana cara membatalkan booking?',
                'jawaban'    => 'Buka menu "Data Booking", temukan booking yang ingin dibatalkan, lalu klik tombol "Cancel" dan isi alasan pembatalan. Pembatalan lebih dari 2 jam sebelum acara tidak akan mengurangi reputation point.',
            ],
            [
                'pertanyaan' => 'Mengapa booking saya ditolak?',
                'jawaban'    => 'Booking bisa ditolak karena: ruang sedang dipakai di jam yang sama, jumlah peserta melebihi kapasitas ruang, reputation point di bawah batas minimum, atau durasi melebihi batas maksimum role kamu.',
            ],
            [
                'pertanyaan' => 'Bagaimana cara meningkatkan reputation point?',
                'jawaban'    => 'Reputation point bertambah dengan: check-in tepat waktu (+3), menggunakan ruang sesuai jadwal (+5), dan mengembalikan kondisi ruang dengan baik (+2). Hindari no-show, pembatalan mendadak, dan penggunaan melebihi waktu.',
            ],
            [
                'pertanyaan' => 'Apakah bisa booking lebih dari satu ruang sekaligus?',
                'jawaban'    => 'Satu pengguna hanya bisa memiliki maksimal 2 booking aktif secara bersamaan. Jika sudah mencapai batas, kamu perlu menyelesaikan atau membatalkan booking yang ada sebelum mengajukan yang baru.',
            ],
            [
                'pertanyaan' => 'Siapa yang bisa melihat jadwal ruang?',
                'jawaban'    => 'Semua pengguna yang sudah login dapat melihat jadwal dan ketersediaan ruang secara real-time melalui menu "Ketersediaan Ruang" dan "Jadwal Ruangan".',
            ],
        ];

        $aturanReputation = [
            [
                'range'  => '80 – 100',
                'label'  => 'Trusted User',
                'warna'  => 'emerald',
                'emoji'  => '⭐',
                'detail' => 'Proses approval lebih cepat, dapat booking hingga 3 slot aktif, prioritas antrian diutamakan.',
            ],
            [
                'range'  => '50 – 79',
                'label'  => 'Normal',
                'warna'  => 'blue',
                'emoji'  => '👤',
                'detail' => 'Dapat booking seperti biasa, maksimal 2 slot aktif, proses approval normal.',
            ],
            [
                'range'  => '30 – 49',
                'label'  => 'Dibatasi',
                'warna'  => 'amber',
                'emoji'  => '⚠️',
                'detail' => 'Booking dibatasi hanya 1 slot aktif, wajib melewati review manual admin sebelum disetujui.',
            ],
            [
                'range'  => '< 30',
                'label'  => 'Diblokir',
                'warna'  => 'red',
                'emoji'  => '🚫',
                'detail' => 'Tidak dapat mengajukan booking sementara. Hubungi admin untuk pemulihan akun.',
            ],
        ];

        $panduanLangkah = [
            [
                'nomor'  => '01',
                'judul'  => 'Login ke Sistem',
                'detail' => 'Masuk menggunakan email dan password yang terdaftar. Role kamu (admin, dosen, mahasiswa, organisasi) akan terbaca otomatis.',
                'icon'   => '🔑',
            ],
            [
                'nomor'  => '02',
                'judul'  => 'Cek Ketersediaan Ruang',
                'detail' => 'Buka menu "Ketersediaan Ruang" untuk melihat status real-time semua ruang. Gunakan filter dan pencarian untuk menemukan ruang yang sesuai.',
                'icon'   => '🏫',
            ],
            [
                'nomor'  => '03',
                'judul'  => 'Ajukan Booking',
                'detail' => 'Isi formulir booking: pilih ruang, tanggal, jam mulai-selesai, jumlah peserta, dan tujuan kegiatan. Pastikan durasi sesuai batas role kamu.',
                'icon'   => '📝',
            ],
            [
                'nomor'  => '04',
                'judul'  => 'Tunggu Approval',
                'detail' => 'Admin akan memproses pengajuan dalam 1×24 jam. Kamu akan mendapat notifikasi saat booking disetujui atau ditolak.',
                'icon'   => '⏳',
            ],
            [
                'nomor'  => '05',
                'judul'  => 'Check-in Tepat Waktu',
                'detail' => 'Saat hari H, lakukan check-in melalui sistem maksimal 15 menit setelah jam mulai. Check-in tepat waktu menambah reputation point.',
                'icon'   => '✅',
            ],
            [
                'nomor'  => '06',
                'judul'  => 'Selesai & Evaluasi',
                'detail' => 'Setelah selesai menggunakan ruang, pastikan kondisi ruang kembali seperti semula. Reputation point akan diperbarui otomatis oleh sistem.',
                'icon'   => '🏆',
            ],
        ];

        return view('help.index', compact(
            'faqs',
            'aturanReputation',
            'panduanLangkah'
        ));
    }
}