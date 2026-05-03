<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        // sementara dummy user (nanti diganti auth user)
        $user = (object) [
            'name' => 'I Made Syaeful Gahar',
            'role' => 'Admin',
            'email' => 'admin@kampus.ac.id',
            'point' => 85,
            'status' => 'Trusted', // Trusted / Limited / Banned
            'booking_active' => 3,
            'violation' => 0,
        ];

        // log aktivitas / riwayat singkat (dummy)
        $activities = [
            'Booking R-201 disetujui',
            'Check-in LAB-01 berhasil',
            'Mengajukan booking R-105',
        ];

        return view('profile.index', compact('user', 'activities'));
    }
}