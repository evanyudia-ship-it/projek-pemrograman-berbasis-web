<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitiesTableSeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['nama' => 'Proyektor HD', 'icon' => '📽️', 'kategori' => 'Elektronik', 'deskripsi' => 'Proyektor resolusi Full HD 1080p'],
            ['nama' => 'AC Central', 'icon' => '❄️', 'kategori' => 'Elektronik', 'deskripsi' => 'Sistem pendingin ruangan central'],
            ['nama' => 'WiFi 100Mbps', 'icon' => '📶', 'kategori' => 'Jaringan', 'deskripsi' => 'Koneksi internet cepat 100 Mbps'],
            ['nama' => 'Sound System', 'icon' => '🔊', 'kategori' => 'Audio', 'deskripsi' => 'Sistem audio dengan speaker surround'],
            ['nama' => 'Whiteboard', 'icon' => '📋', 'kategori' => 'Perabotan', 'deskripsi' => 'Papan tulis putih ukuran besar'],
            ['nama' => 'TV 65"', 'icon' => '🖥️', 'kategori' => 'Elektronik', 'deskripsi' => 'TV layar datar 65 inch 4K'],
            ['nama' => 'Meja Rapat', 'icon' => '🪑', 'kategori' => 'Perabotan', 'deskripsi' => 'Meja rapat oval kapasitas 20 orang'],
            ['nama' => 'Kursi Mahasiswa', 'icon' => '🪑', 'kategori' => 'Perabotan', 'deskripsi' => 'Kursi ergonomis untuk mahasiswa'],
            ['nama' => 'Podium', 'icon' => '🎤', 'kategori' => 'Perabotan', 'deskripsi' => 'Podium untuk pembicara/presenter'],
            ['nama' => 'Webcam 4K', 'icon' => '📹', 'kategori' => 'Elektronik', 'deskripsi' => 'Webcam resolusi 4K untuk hybrid meeting'],
            ['nama' => 'UPS', 'icon' => '⚡', 'kategori' => 'Elektronik', 'deskripsi' => 'Uninterruptible Power Supply untuk komputer'],
            ['nama' => 'Papan Tulis', 'icon' => '📋', 'kategori' => 'Perabotan', 'deskripsi' => 'Papan tulis kapur ukuran standar'],
            ['nama' => 'AC Split', 'icon' => '❄️', 'kategori' => 'Elektronik', 'deskripsi' => 'AC split 2 PK'],
            ['nama' => 'Mikrofon', 'icon' => '🎤', 'kategori' => 'Audio', 'deskripsi' => 'Mikrofon wireless untuk presentasi'],
            ['nama' => 'Lighting', 'icon' => '💡', 'kategori' => 'Elektronik', 'deskripsi' => 'Sistem pencahayaan ruangan'],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}
