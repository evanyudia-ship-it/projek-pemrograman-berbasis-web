<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultiesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faculties = [
            [
                'name' => 'Fakultas Teknik dan Kejuruan',
                'code' => 'FTK',
                'description' => 'Fakultas Teknik dan Kejuruan Universitas Pendidikan Ganesha',
                'status' => 'active',
            ],
            [
                'name' => 'Fakultas Ilmu Pendidikan',
                'code' => 'FIP',
                'description' => 'Fakultas Ilmu Pendidikan Universitas Pendidikan Ganesha',
                'status' => 'active',
            ],
            [
                'name' => 'Fakultas Bahasa dan Seni',
                'code' => 'FBS',
                'description' => 'Fakultas Bahasa dan Seni Universitas Pendidikan Ganesha',
                'status' => 'active',
            ],
            [
                'name' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'code' => 'FMIPA',
                'description' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Pendidikan Ganesha',
                'status' => 'active',
            ],
            [
                'name' => 'Fakultas Ilmu Sosial dan Hukum',
                'code' => 'FISH',
                'description' => 'Fakultas Ilmu Sosial dan Hukum Universitas Pendidikan Ganesha',
                'status' => 'active',
            ],
            [
                'name' => 'Fakultas Ekonomi',
                'code' => 'FE',
                'description' => 'Fakultas Ekonomi Universitas Pendidikan Ganesha',
                'status' => 'active',
            ],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create($faculty);
        }
    }
}
