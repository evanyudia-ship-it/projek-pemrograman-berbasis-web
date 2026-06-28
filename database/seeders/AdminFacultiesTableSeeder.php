<?php

namespace Database\Seeders;

use App\Models\AdminFaculty;
use Illuminate\Database\Seeder;

class AdminFacultiesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Admin (user_id=2) mengelola FTK (faculty_id=1)
        AdminFaculty::create([
            'user_id' => 2,
            'faculty_id' => 1,
            'position' => 'Validator Fakultas Teknik',
            'status' => 'active',
        ]);

        // Admin (user_id=2) mengelola FIP (faculty_id=2)
        AdminFaculty::create([
            'user_id' => 2,
            'faculty_id' => 2,
            'position' => 'Validator Fakultas Ilmu Pendidikan',
            'status' => 'active',
        ]);
    }
}
