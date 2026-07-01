<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // SUPER ADMIN
        // ============================================================
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@undiksha.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'status' => 'active',
            'reputation_points' => 100,
            'email_verified_at' => now(),
        ]);

        // ============================================================
        // ADMIN
        // ============================================================
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@undiksha.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
            'reputation_points' => 100,
            'email_verified_at' => now(),
        ]);

        // ============================================================
        // DOSEN
        // ============================================================
        User::create([
            'name' => 'Dr. I Wayan Surya, S.Kom., M.Kom.',
            'email' => 'dosen@undiksha.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'dosen',
            'nidn' => '199001012019031001',
            'phone' => '081234567890',
            'faculty_id' => 1,
            'status' => 'active',
            'reputation_points' => 85,
            'email_verified_at' => now(),
        ]);

        // ============================================================
        // MAHASISWA
        // ============================================================
        // Mahasiswa 1 - Reputasi Baik
        User::create([
            'name' => 'I Made Syaeful Anwar',
            'email' => 'mahasiswa1@undiksha.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
            'nim' => '2015091001',
            'phone' => '081234567891',
            'faculty_id' => 1,
            'status' => 'active',
            'reputation_points' => 75,
            'email_verified_at' => now(),
        ]);

        // Mahasiswa 2 - Reputasi Sedang
        User::create([
            'name' => 'Ni Luh Putu Dewi',
            'email' => 'mahasiswa2@undiksha.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
            'nim' => '2015091023',
            'phone' => '081234567892',
            'faculty_id' => 1,
            'status' => 'active',
            'reputation_points' => 60,
            'email_verified_at' => now(),
        ]);

        // Mahasiswa 3 - Reputasi Rendah (Dibatasi)
        User::create([
            'name' => 'Komang Satria Pratama',
            'email' => 'mahasiswa3@undiksha.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
            'nim' => '2015091015',
            'phone' => '081234567893',
            'faculty_id' => 2,
            'status' => 'active',
            'reputation_points' => 45,
            'email_verified_at' => now(),
        ]);

        // Mahasiswa 4 - Reputasi Kritis (Banned karena reputasi < 30)
        User::create([
            'name' => 'Ketut Arya Wiguna',
            'email' => 'mahasiswa4@undiksha.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
            'nim' => '2015091099',
            'phone' => '081234567894',
            'faculty_id' => 2,
            'status' => 'banned', // ← BANNED KARENA REPUTASI RENDAH
            'reputation_points' => 25,
            'email_verified_at' => now(),
        ]);

        // ============================================================
        // ORGANISASI
        // ============================================================
        User::create([
            'name' => 'Himpunan Mahasiswa Teknik Informatika',
            'email' => 'hmtifo@undiksha.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'organisasi',
            'phone' => '081234567899',
            'faculty_id' => 1,
            'status' => 'active',
            'reputation_points' => 80,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'BEM Universitas Undiksha',
            'email' => 'bem@undiksha.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'organisasi',
            'phone' => '089876543210',
            'faculty_id' => null,
            'status' => 'active',
            'reputation_points' => 70,
            'email_verified_at' => now(),
        ]);

        // ============================================================
        // AKUN DEMO BANNED - UNTUK TESTING
        // ============================================================

        // Demo Banned 1: Mahasiswa dengan reputasi 15 (banned)
        User::create([
            'name' => 'Demo Banned - Mahasiswa',
            'email' => 'banned@undiksha.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
            'nim' => '2015091111',
            'phone' => '081234567895',
            'faculty_id' => 1,
            'status' => 'banned',
            'reputation_points' => 15,
            'email_verified_at' => now(),
        ]);

        // Demo Banned 2: Organisasi dengan reputasi 10 (banned)
        User::create([
            'name' => 'Demo Banned - Organisasi',
            'email' => 'banned-org@undiksha.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'organisasi',
            'phone' => '081234567897',
            'faculty_id' => 2,
            'status' => 'banned',
            'reputation_points' => 10,
            'email_verified_at' => now(),
        ]);

        // ============================================================
        // AKUN PENDING
        // ============================================================
        User::create([
            'name' => 'User Pending',
            'email' => 'pending@undiksha.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
            'nim' => '2015091122',
            'phone' => '081234567896',
            'faculty_id' => 1,
            'status' => 'pending',
            'reputation_points' => 100,
        ]);
    }
}
