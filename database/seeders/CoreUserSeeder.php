<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CoreUserSeeder extends Seeder
{
    public function run(): void
    {
        $faculty = Faculty::firstOrCreate(
            ['code' => 'FT'],
            [
                'name' => 'Fakultas Teknik',
                'description' => 'Fakultas Teknik',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'phone' => '080000000001',
                'faculty_id' => $faculty->id,
                'status' => 'active',
                'email_verified_at' => now(),
                'reputation_points' => 100,
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Fakultas',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '080000000002',
                'faculty_id' => $faculty->id,
                'status' => 'active',
                'email_verified_at' => now(),
                'reputation_points' => 100,
            ]
        );

        User::firstOrCreate(
            ['email' => 'dosen@example.com'],
            [
                'name' => 'Dosen Demo',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'nidn' => '1234567890',
                'phone' => '080000000003',
                'faculty_id' => $faculty->id,
                'status' => 'active',
                'email_verified_at' => now(),
                'reputation_points' => 100,
            ]
        );

        User::firstOrCreate(
            ['email' => 'mahasiswa@example.com'],
            [
                'name' => 'Mahasiswa Demo',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'nim' => '20260001',
                'phone' => '080000000004',
                'faculty_id' => $faculty->id,
                'status' => 'active',
                'email_verified_at' => now(),
                'reputation_points' => 100,
            ]
        );
    }
}