<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('faculty')->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('nidn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(10)->withQueryString();

        $totalUsers = User::count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalDosen = User::where('role', 'dosen')->count();
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalOrganisasi = 0;

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'totalAdmin',
            'totalDosen',
            'totalMahasiswa',
            'totalOrganisasi'
        ));
    }

    public function create()
    {
        $faculties = Faculty::where('status', 'active')->orderBy('name')->get();

        return view('admin.users.create', compact('faculties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', Rule::in(['superadmin', 'admin', 'dosen', 'mahasiswa'])],
            'nim' => ['nullable', 'string', 'max:30', 'unique:users,nim'],
            'nidn' => ['nullable', 'string', 'max:30', 'unique:users,nidn'],
            'phone' => ['nullable', 'string', 'max:30'],
            'faculty_id' => ['nullable', 'exists:faculties,id'],
            'status' => ['required', Rule::in(['pending', 'active', 'inactive', 'banned'])],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['reputation_points'] = $validated['reputation_points'] ?? 100;

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load('faculty', 'managedFaculties', 'reputationLogs');

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $faculties = Faculty::where('status', 'active')->orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'faculties'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role' => ['required', Rule::in(['superadmin', 'admin', 'dosen', 'mahasiswa'])],
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
            'status' => ['required', Rule::in(['pending', 'active', 'inactive', 'banned'])],
            'reputation_points' => ['nullable', 'integer', 'min:0'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (session('user_id') == $user->id) {
            return back()->with('error', 'Akun yang sedang digunakan tidak boleh dihapus.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}