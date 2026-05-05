<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function show()
    {
        // Sudah verified → tidak perlu ke sini
        if (session('is_verified')) {
            return redirect()->route('user.dashboard');
        }
    
        // Ada reg_email → tampilkan halaman OTP
        if (session('reg_email')) {
            // Flash ulang OTP supaya banner dev tetap muncul
            session()->flash('otp_demo', session('reg_otp'));
            
            return view('auth.verify');
        }
    
        // Tidak ada reg_email sama sekali
        return redirect()->route('register')->withErrors([
            'email' => 'Silakan daftar terlebih dahulu.',
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits'   => 'Kode OTP harus 6 digit angka.',
        ]);

        $inputOtp = $request->otp;
        $savedOtp = session('reg_otp');
        $otpAt    = session('reg_otp_at');

        if (now()->timestamp - $otpAt > 600) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.']);
        }

        if ($inputOtp !== $savedOtp) {
            return back()->withErrors(['otp' => 'Kode OTP salah. Silakan coba lagi.']);
        }

        // Kalau user belum login (flow register baru), set semua session
        if (!session('logged_in')) {
            session([
                'user_id'    => time(),
                'user_name'  => session('reg_name'),
                'user_email' => session('reg_email'),
                'user_role'  => session('reg_role'),
                'logged_in'  => true,
            ]);
        }

        // Set verified (berlaku untuk user baru maupun user lama dari profile)
        session(['is_verified' => true]);

        // Bersihkan semua reg_* session
        session()->forget([
            'reg_name', 'reg_email', 'reg_role',
            'reg_nim_nip', 'reg_password',
            'reg_otp', 'reg_otp_at', 'reg_verified',
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'Akun berhasil diverifikasi! Selamat datang 🎉');
    }

    public function resend()
    {
        if (!session('reg_email')) {
            return redirect()->route('register');
        }

        // Generate OTP baru
        $otp = strval(rand(100000, 999999));

        session([
            'reg_otp'    => $otp,
            'reg_otp_at' => now()->timestamp,
        ]);

        session()->flash('otp_demo', $otp);
        session()->flash('resent', true);

        return redirect()->route('verify.show');
    }
}