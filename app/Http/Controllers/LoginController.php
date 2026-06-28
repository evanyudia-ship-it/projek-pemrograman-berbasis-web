<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        // Jika sudah login, redirect ke dashboard
        if (session('logged_in') || Auth::check()) {
            $role = session('user_role', Auth::user()?->role ?? 'mahasiswa');

            return match ($role) {
                'superadmin' => redirect()->route('dashboard'),
                'admin' => redirect()->route('admin.dashboard'),
                default => redirect()->route('user.dashboard'),
            };
        }

        // Tampilkan halaman login (standalone, tanpa layout)
        return view('auth.login');
    }

    public function process(Request $request)
    {
        $validated = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ],
            [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'password.required' => 'Password wajib diisi.',
            ]
        );

        $user = User::where('email', strtolower($validated['email']))->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        if ($user->status === 'inactive') {
            return back()
                ->withErrors(['email' => 'Akun Anda sedang dinonaktifkan oleh administrator.'])
                ->onlyInput('email');
        }

        if ($user->status === 'banned') {
            return back()
                ->withErrors(['email' => 'Akun Anda dibatasi karena reputasi atau pelanggaran penggunaan.'])
                ->onlyInput('email');
        }

        $credentials = [
            'email' => strtolower($validated['email']),
            'password' => $validated['password'],
        ];

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'logged_in' => true,
            'is_verified' => !is_null($user->email_verified_at),
        ]);

        if (is_null($user->email_verified_at)) {
            $otp = strval(rand(100000, 999999));

            session([
                'reg_email' => $user->email,
                'reg_otp' => $otp,
                'reg_otp_at' => now()->timestamp,
            ]);

            session()->flash('otp_demo', $otp);

            return redirect()
                ->route('verify.show')
                ->with('warning', 'Silakan verifikasi email terlebih dahulu.');
        }

        return match ($user->role) {
            'superadmin' => redirect()->route('dashboard')->with('success', 'Selamat datang, ' . $user->name . '!'),
            'admin' => redirect()->route('admin.dashboard')->with('success', 'Selamat datang, ' . $user->name . '!'),
            default => redirect()->route('user.dashboard')->with('success', 'Selamat datang, ' . $user->name . '!'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus semua session
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke login dengan flash message
        return redirect()
            ->route('login')
            ->with('success', 'Anda berhasil logout.');
    }




}
