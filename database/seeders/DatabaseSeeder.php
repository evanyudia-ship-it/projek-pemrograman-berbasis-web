<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // ============================================================
            // 1. DATA MASTER (Harus dibuat dulu)
            // ============================================================
            FacultiesTableSeeder::class,           // ✅ FACULTIES DULUAN
            ReputationLevelsTableSeeder::class,    // ✅ Level reputasi
            ReputationSettingsTableSeeder::class,  // ✅ Setting reputasi
            FacilitiesTableSeeder::class,          // ✅ Fasilitas

            // ============================================================
            // 2. USERS (Bergantung pada faculties)
            // ============================================================
            UsersTableSeeder::class,               // ✅ SEKARANG BISA AKSES faculty_id

            // ============================================================
            // 3. DATA YANG BERGANTUNG PADA USERS
            // ============================================================
            AdminFacultiesTableSeeder::class,      // Bergantung pada users & faculties
            RoomsTableSeeder::class,               // Bergantung pada faculties (faculty_id)

            // ============================================================
            // 4. DATA YANG BERGANTUNG PADA ROOMS & USERS
            // ============================================================
            RoomFacilitiesTableSeeder::class,      // Bergantung pada rooms & facilities
            BookingsTableSeeder::class,            // Bergantung pada users & rooms

            // ============================================================
            // 5. DATA LAINNYA
            // ============================================================
            NotificationsTableSeeder::class,       // Bergantung pada users
            HelpSeeder::class,                     // Data statis
        ]);
    }
}
