<?php

namespace Database\Seeders;

use App\Models\RoomFacility;
use Illuminate\Database\Seeder;

class RoomFacilitiesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Ruang Seminar A (room_id=1) - 7 fasilitas
        $room1 = [
            ['room_id' => 1, 'facility_id' => 1, 'status' => 'tersedia'],  // Proyektor HD
            ['room_id' => 1, 'facility_id' => 2, 'status' => 'tersedia'],  // AC Central
            ['room_id' => 1, 'facility_id' => 3, 'status' => 'tersedia'],  // WiFi
            ['room_id' => 1, 'facility_id' => 4, 'status' => 'tersedia'],  // Sound System
            ['room_id' => 1, 'facility_id' => 5, 'status' => 'tersedia'],  // Whiteboard
            ['room_id' => 1, 'facility_id' => 9, 'status' => 'tersedia'],  // Podium
            ['room_id' => 1, 'facility_id' => 10, 'status' => 'tersedia'], // Webcam 4K
        ];

        // Ruang Rapat 205 (room_id=2) - 5 fasilitas
        $room2 = [
            ['room_id' => 2, 'facility_id' => 7, 'status' => 'tersedia'],  // Meja Rapat
            ['room_id' => 2, 'facility_id' => 2, 'status' => 'tersedia'],  // AC Central
            ['room_id' => 2, 'facility_id' => 6, 'status' => 'tersedia'],  // TV 65"
            ['room_id' => 2, 'facility_id' => 3, 'status' => 'tersedia'],  // WiFi
            ['room_id' => 2, 'facility_id' => 5, 'status' => 'tersedia'],  // Whiteboard
        ];

        // Ruang Kuliah B-12 (room_id=3) - 4 fasilitas
        $room3 = [
            ['room_id' => 3, 'facility_id' => 13, 'status' => 'tersedia'], // AC Split
            ['room_id' => 3, 'facility_id' => 1, 'status' => 'tersedia'],  // Proyektor HD
            ['room_id' => 3, 'facility_id' => 8, 'status' => 'tersedia'],  // Kursi Mahasiswa
            ['room_id' => 3, 'facility_id' => 12, 'status' => 'tersedia'], // Papan Tulis
        ];

        // Lab Komputer C-03 (room_id=4) - 5 fasilitas
        $room4 = [
            ['room_id' => 4, 'facility_id' => 11, 'status' => 'tersedia'], // UPS
            ['room_id' => 4, 'facility_id' => 13, 'status' => 'tersedia'], // AC Split
            ['room_id' => 4, 'facility_id' => 1, 'status' => 'tersedia'],  // Proyektor HD
            ['room_id' => 4, 'facility_id' => 3, 'status' => 'tersedia'],  // WiFi
            ['room_id' => 4, 'facility_id' => 8, 'status' => 'tersedia'],  // Kursi Mahasiswa
        ];

        // Aula Utama (room_id=5) - 5 fasilitas
        $room5 = [
            ['room_id' => 5, 'facility_id' => 2, 'status' => 'maintenance'], // AC Central (rusak)
            ['room_id' => 5, 'facility_id' => 4, 'status' => 'tersedia'],   // Sound System
            ['room_id' => 5, 'facility_id' => 9, 'status' => 'tersedia'],   // Podium
            ['room_id' => 5, 'facility_id' => 14, 'status' => 'tersedia'],  // Mikrofon
            ['room_id' => 5, 'facility_id' => 15, 'status' => 'tersedia'],  // Lighting
        ];

        // Meeting Room (room_id=6) - 5 fasilitas
        $room6 = [
            ['room_id' => 6, 'facility_id' => 6, 'status' => 'tersedia'],  // TV 65"
            ['room_id' => 6, 'facility_id' => 13, 'status' => 'tersedia'], // AC Split
            ['room_id' => 6, 'facility_id' => 3, 'status' => 'tersedia'],  // WiFi
            ['room_id' => 6, 'facility_id' => 5, 'status' => 'tersedia'],  // Whiteboard
            ['room_id' => 6, 'facility_id' => 8, 'status' => 'tersedia'],  // Kursi Mahasiswa
        ];

        $allFacilities = array_merge($room1, $room2, $room3, $room4, $room5, $room6);

        foreach ($allFacilities as $data) {
            RoomFacility::create($data);
        }
    }
}
