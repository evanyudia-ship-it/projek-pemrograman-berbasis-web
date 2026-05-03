<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'ruang_tersedia'   => 12,
            'total_ruang'      => 28,
            'booking_aktif'    => 3,
            'menunggu_approval'=> 2,
            'reputation_point' => 245,
            'jadwal_hari_ini'  => [
                [
                    'id' => 1,
                    'ruangan' => 'Ruang Seminar A - Lt. 3',
                    'waktu' => '10:00 - 12:00',
                    'durasi' => '2 jam',
                    'keperluan' => 'Pemrograman Web Lanjutan',
                    'status' => 'Confirmed'
                ],
                [
                    'id' => 2,
                    'ruangan' => 'Ruang Rapat 205',
                    'waktu' => '13:30 - 15:00',
                    'durasi' => '1.5 jam',
                    'keperluan' => 'Rapat Organisasi Mahasiswa',
                    'status' => 'Pending'
                ]
            ]
        ];

        return view('dashboard', $data);
    }
}