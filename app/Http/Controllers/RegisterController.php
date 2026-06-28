<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function showForm()
    {
        if (Auth::check() || session('logged_in')) {
            return redirect()->route('user.dashboard');
        }

        $faculties = Faculty::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('auth.register', compact('faculties'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'min:3', 'max:100'],
                'email' => ['required', 'email', 'max:150', 'unique:users,email'],
                'role' => ['required', Rule::in(['mahasiswa', 'dosen'])],
                'nim_nip' => ['required', 'string', 'max:30'],
                'phone' => ['nullable', 'string', 'max:30'],
                'faculty_id' => ['nullable', 'exists:faculties,id'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ],
            [
                'name.required' => 'Nama wajib diisi.',
                'name.min' => 'Nama minimal 3 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'role.required' => 'Role wajib dipilih.',
                'role.in' => 'Role tidak valid.',
                'nim_nip.required' => 'NIM/NIDN wajib diisi.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]
        );

        $nim = null;
        $nidn = null;

        if ($validated['role'] === 'mahasiswa') {
            $nim = $validated['nim_nip'];

            if (User::where('nim', $nim)->exists()) {
                return back()
                    ->withErrors(['nim_nip' => 'NIM sudah terdaftar.'])
                    ->withInput();
            }
        }

        if ($validated['role'] === 'dosen') {
            $nidn = $validated['nim_nip'];

            if (User::where('nidn', $nidn)->exists()) {
                return back()
                    ->withErrors(['nim_nip' => 'NIDN sudah terdaftar.'])
                    ->withInput();
            }
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'nim' => $nim,
            'nidn' => $nidn,
            'phone' => $validated['phone'] ?? null,
            'faculty_id' => $validated['faculty_id'] ?? null,
            'status' => 'pending',
            'reputation_points' => 100,
        ]);

        Auth::login($user);

        $otp = strval(rand(100000, 999999));

        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'logged_in' => true,
            'is_verified' => false,

            'reg_email' => $user->email,
            'reg_otp' => $otp,
            'reg_otp_at' => now()->timestamp,
        ]);

        session()->flash('otp_demo', $otp);

        return redirect()
            ->route('verify.show')
            ->with('success', 'Registrasi berhasil. Silakan verifikasi email Anda.');
    }
}