<?php

namespace Database\Seeders;

use App\Models\ReputationLevel;
use Illuminate\Database\Seeder;

class ReputationLevelsTableSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Trusted User',
                'min_points' => 80,
                'max_points' => 100,
                'color' => '#10b981',
                'description' => 'Pengguna dengan reputasi sangat baik. Mendapat prioritas approval dan booking lebih banyak.',
                'is_banned' => false,
                'status' => 'active',
            ],
            [
                'name' => 'Normal',
                'min_points' => 50,
                'max_points' => 79,
                'color' => '#3b82f6',
                'description' => 'Pengguna dengan reputasi normal. Dapat booking seperti biasa.',
                'is_banned' => false,
                'status' => 'active',
            ],
            [
                'name' => 'Dibatasi',
                'min_points' => 30,
                'max_points' => 49,
                'color' => '#f59e0b',
                'description' => 'Pengguna dengan reputasi rendah. Booking dibatasi dan harus melalui review manual.',
                'is_banned' => false,
                'status' => 'active',
            ],
            [
                'name' => 'Diblokir',
                'min_points' => 0,
                'max_points' => 29,
                'color' => '#ef4444',
                'description' => 'Pengguna diblokir karena reputasi sangat rendah. Tidak dapat melakukan booking.',
                'is_banned' => true,
                'status' => 'active',
            ],
        ];

        foreach ($levels as $level) {
            ReputationLevel::updateOrCreate(
                ['name' => $level['name']],
                $level
            );
        }
    }
}
