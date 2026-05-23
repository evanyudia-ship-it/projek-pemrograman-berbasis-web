<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        // Data Dummy untuk Statistik
        $totalUsers      = 5;
        $totalAdmin      = 1;
        $totalDosen      = 1;
        $totalMahasiswa  = 2;
        $totalOrganisasi = 1;

        return view('admin.users.index', compact(
            'totalUsers',
            'totalAdmin',
            'totalDosen',
            'totalMahasiswa',
            'totalOrganisasi'
            
        ));
    }
}