<?php

namespace App\Http\Controllers;

class MahasiswaController extends Controller
{
    public function index()
    {
        return view('Mahasiswa');
    }

    public function show($nim)
    {
        return view('Mahasiswa', compact('nim'));
    }
}
