<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyController extends Controller
{
    public function show()
    {
        if (session('is_verified')) {
            $role = session('user_role');

            return match ($role) {
                'superadmin' => redirect()->route('dashboard'),
                'admin' => redirect()->route('admin.dashboard'),
                default => redirect()->route('user.dashboard'),
            };
        }

        if (!session('reg_email')) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Silakan login atau daftar terlebih dahulu.']);
        }

        session()->flash('otp_demo', session('reg_otp'));

        return view('auth.verify');
    }

    public function process(Request $request)
    {
        $validated = $request->validate(
            [
                'otp' => ['required', 'digits:6'],
            ],
            [
                'otp.required' => 'Kode OTP wajib diisi.',
                'otp.digits' => 'Kode OTP harus 6 digit angka.',
            ]
        );

        $savedOtp = session('reg_otp');
        $otpAt = session('reg_otp_at');

        if (!$savedOtp || !$otpAt) {
            return redirect()
                ->route('verify.show')
                ->withErrors(['otp' => 'Kode OTP tidak ditemukan. Silakan kirim ulang kode OTP.']);
        }

        if (now()->timestamp - $otpAt > 600) {
            return back()->withErrors([
                'otp' => 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.',
            ]);
        }

        if ($validated['otp'] !== $savedOtp) {
            return back()->withErrors([
                'otp' => 'Kode OTP salah. Silakan coba lagi.',
            ]);
        }

        $email = session('reg_email');

        $user = Auth::user();

        if (!$user) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'User tidak ditemukan. Silakan login ulang.']);
        }

        $user->update([
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        Auth::login($user);

        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'logged_in' => true,
            'is_verified' => true,
        ]);

        session()->forget([
            'reg_email',
            'reg_otp',
            'reg_otp_at',
        ]);

        return match ($user->role) {
            'superadmin' => redirect()
                ->route('dashboard')
                ->with('success', 'Akun berhasil diverifikasi.'),
            'admin' => redirect()
                ->route('admin.dashboard')
                ->with('success', 'Akun berhasil diverifikasi.'),
            default => redirect()
                ->route('user.dashboard')
                ->with('success', 'Akun berhasil diverifikasi.'),
        };
    }

    public function resend()
    {
        if (!session('reg_email')) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Silakan login terlebih dahulu.']);
        }

        $otp = strval(rand(100000, 999999));

        session([
            'reg_otp' => $otp,
            'reg_otp_at' => now()->timestamp,
        ]);

        session()->flash('otp_demo', $otp);
        session()->flash('resent', true);

        return redirect()
            ->route('verify.show')
            ->with('success', 'Kode OTP baru berhasil dikirim.');
    }
}