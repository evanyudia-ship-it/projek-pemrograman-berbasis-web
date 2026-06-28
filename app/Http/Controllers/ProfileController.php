<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    private function currentUser(): ?User
    {
        if (Auth::check()) {
            return Auth::user();
        }

        if (session()->has('user_id')) {
            return User::find(session('user_id'));
        }

        return null;
    }

    public function index()
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $faculties = Faculty::where('status', 'active')->orderBy('name')->get();

        return view('profile.index', compact('user', 'faculties'));
    }

    public function update(Request $request)
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'nim' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('users', 'nim')->ignore($user->id),
            ],
            'nidn' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('users', 'nidn')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'faculty_id' => ['nullable', 'exists:faculties,id'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}