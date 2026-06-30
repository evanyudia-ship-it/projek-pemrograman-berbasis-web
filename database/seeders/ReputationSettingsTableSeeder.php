<?php

namespace Database\Seeders;

use App\Models\ReputationSetting;
use Illuminate\Database\Seeder;

class ReputationSettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // REWARDS
            [
                'code' => 'CHECK_IN_ON_TIME',
                'name' => 'Check-in Tepat Waktu',
                'type' => 'reward',
                'point' => 5,
                'description' => 'Bonus reputasi karena melakukan check-in tepat waktu (≤ 15 menit dari jam mulai)',
                'is_active' => true,
            ],
            [
                'code' => 'ROOM_USAGE_GOOD',
                'name' => 'Penggunaan Ruang Baik',
                'type' => 'reward',
                'point' => 10,
                'description' => 'Bonus reputasi karena menyelesaikan booking tepat waktu',
                'is_active' => true,
            ],
            [
                'code' => 'BOOKING_APPROVED',
                'name' => 'Booking Disetujui',
                'type' => 'reward',
                'point' => 5,
                'description' => 'Bonus reputasi karena booking disetujui admin',
                'is_active' => true,
            ],

            // PENALTIES
            [
                'code' => 'NO_SHOW',
                'name' => 'No Show',
                'type' => 'penalty',
                'point' => -15,
                'description' => 'Penalti karena tidak melakukan check-in sama sekali',
                'is_active' => true,
            ],
            [
                'code' => 'CANCEL_SUDDEN',
                'name' => 'Pembatalan Mendadak',
                'type' => 'penalty',
                'point' => -10,
                'description' => 'Penalti karena membatalkan booking mendadak (< 1 jam sebelum)',
                'is_active' => true,
            ],
            [
                'code' => 'CHECK_IN_LATE',
                'name' => 'Check-in Terlambat',
                'type' => 'penalty',
                'point' => -5,
                'description' => 'Penalti karena check-in terlambat (> 15 menit setelah jam mulai)',
                'is_active' => true,
            ],
            [
                'code' => 'FAKE_BOOKING',
                'name' => 'Booking Fiktif',
                'type' => 'penalty',
                'point' => -20,
                'description' => 'Penalti berat karena membuat booking fiktif/palsu',
                'is_active' => true,
            ],
        ];

        foreach ($settings as $setting) {
            ReputationSetting::updateOrCreate(
                ['code' => $setting['code']],
                $setting
            );
        }
    }
}
