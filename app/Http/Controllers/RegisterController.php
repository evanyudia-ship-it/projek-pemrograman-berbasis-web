<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function showForm()
    {
        // Sudah login → tidak perlu register lagi
        if (session('logged_in')) {
            return redirect()->route('user.dashboard');
        }
    
        return view('auth.register');
    }

    public function process(Request $request)
    {
        $request->validate(
            [
                'name'      => 'required|min:3|max:100',
                'email'     => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@student.undiksha.ac.id$/'],
                'role'      => 'required|in:mahasiswa,dosen',
                'nim_nip'   => 'required|string|max:30',
                'password'  => 'required|min:6|confirmed',
            ],
            [
                'name.required'      => 'Nama wajib diisi.',
                'name.min'           => 'Nama minimal 3 karakter.',
    
                'email.required'     => 'Email wajib diisi.',
                'email.email'        => 'Format email tidak valid.',
                'email.regex'        => 'Gunakan email kampus (@student.undiksha.ac.id).',
    
                'role.required'      => 'Role wajib dipilih.',
                'role.in'            => 'Role tidak valid.',
    
                'nim_nip.required'   => 'NIM/NIP wajib diisi.',
    
                'password.required'  => 'Password wajib diisi.',
                'password.min'       => 'Password minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]
        );


        $loginCtrl = new LoginController();
        $existingUsers = $loginCtrl->getUsers();

        foreach ($existingUsers as $user) {
            if (strtolower($user['email'] ?? '') === strtolower($request->email)) {
                return back()->withErrors(['email' => 'Email sudah terdaftar.'])->withInput();
            }
        }

        // Generate OTP (disimpan dulu, tapi tidak redirect ke verify sekarang)
        $otp = strval(rand(100000, 999999));

        // Langsung login, tapi is_verified = false
        session([
            'user_id'      => time(),
            'user_name'    => $request->name,
            'user_email'   => strtolower($request->email),
            'user_nim_nip' => $request->nim_nip,
            'user_role'    => $request->role,
            'logged_in'    => true,
            'is_verified'  => false,   // belum verified

            // Simpan reg_* untuk dipakai saat user klik verify nanti
            'reg_name'     => $request->name,
            'reg_email'    => strtolower($request->email),
            'reg_role'     => $request->role,
            'reg_nim_nip'  => $request->nim_nip,
            'reg_otp'      => $otp,
            'reg_otp_at'   => now()->timestamp,
        ]);

        session()->flash('otp_demo', $otp); // dev only

        // Langsung ke dashboard, verifikasi bisa nanti dari profile
        return redirect()->route('user.dashboard')
                        ->with('success', 'Registrasi berhasil! Silakan verifikasi email Anda. 🎉');
    }
}