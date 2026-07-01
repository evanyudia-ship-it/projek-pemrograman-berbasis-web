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
                'role' => ['required', Rule::in(['mahasiswa', 'dosen', 'organisasi'])],
                'nim_nip' => ['nullable', 'string', 'max:30'], // ← PERBAIKAN: nullable untuk organisasi
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
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]
        );

        // ============================================================
        // VALIDASI NIM/NIDN BERDASARKAN ROLE
        // ============================================================
        $nim = null;
        $nidn = null;

        if ($validated['role'] === 'mahasiswa') {
            // Mahasiswa wajib mengisi NIM
            $request->validate([
                'nim_nip' => ['required', 'string', 'max:30', 'unique:users,nim'],
            ], [
                'nim_nip.required' => 'NIM wajib diisi untuk mahasiswa.',
                'nim_nip.unique' => 'NIM sudah terdaftar.',
            ]);
            $nim = $validated['nim_nip'];
        }

        if ($validated['role'] === 'dosen') {
            // Dosen wajib mengisi NIDN
            $request->validate([
                'nim_nip' => ['required', 'string', 'max:30', 'unique:users,nidn'],
            ], [
                'nim_nip.required' => 'NIDN wajib diisi untuk dosen.',
                'nim_nip.unique' => 'NIDN sudah terdaftar.',
            ]);
            $nidn = $validated['nim_nip'];
        }

        // PERBAIKAN: Organisasi tidak wajib mengisi NIM/NIDN
        // Hapus block yang duplikat dan tidak perlu
        if ($validated['role'] === 'organisasi') {
            // Organisasi tidak perlu NIM/NIDN, simpan sebagai null
            $nim = null;
            $nidn = null;
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
            'reputation_points' => 60,
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
