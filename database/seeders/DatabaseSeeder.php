<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            FacultiesTableSeeder::class,
            AdminFacultiesTableSeeder::class,
            ReputationLevelsTableSeeder::class,
            ReputationSettingsTableSeeder::class,
            RoomsTableSeeder::class,
            FacilitiesTableSeeder::class,
            RoomFacilitiesTableSeeder::class,
            BookingsTableSeeder::class,
            NotificationsTableSeeder::class,
        ]);
    }
}
