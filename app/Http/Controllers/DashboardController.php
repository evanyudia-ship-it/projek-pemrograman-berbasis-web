<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'ruang_tersedia'    => 12,
            'total_ruang'       => 28,
            'booking_aktif'     => 3,
            'menunggu_approval' => 2,
            'reputation_point'  => 85,

            'jadwal_hari_ini' => [
                [
                    'id'        => 1,
                    'ruangan'   => 'Ruang Seminar A - Lt. 3',
                    'kode'      => 'RSA-301',
                    'waktu'     => '10:00 - 12:00',
                    'durasi'    => '2 jam',
                    'keperluan' => 'Pemrograman Web Lanjutan',
                    'status'    => 'Confirmed',
                    // Unsplash foto ruang/interior (bebas dipakai)
                    'foto'      => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=400&q=80',
                ],
                [
                    'id'        => 2,
                    'ruangan'   => 'Ruang Rapat 205',
                    'kode'      => 'RR-205',
                    'waktu'     => '13:30 - 15:00',
                    'durasi'    => '1.5 jam',
                    'keperluan' => 'Rapat Organisasi Mahasiswa',
                    'status'    => 'Pending',
                    'foto'      => 'https://images.unsplash.com/photo-1604328698692-f76ea9498e76?w=400&q=80',
                ],
            ],

            'notifikasi' => [
                [
                    'tipe'   => 'approval',
                    'pesan'  => 'Booking Ruang Seminar A menunggu persetujuan Validator',
                    'waktu'  => '2 jam yang lalu',
                    'icon'   => '⏳',
                ],
                [
                    'tipe'   => 'confirmed',
                    'pesan'  => 'Booking Ruang Kuliah B-12 telah dikonfirmasi',
                    'waktu'  => '5 jam yang lalu',
                    'icon'   => '✅',
                ],
            ],
        ];

        return view('dashboard', $data);
    }
}