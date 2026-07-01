<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminFaculty;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminFacultyController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminFaculty::with(['user', 'faculty'])->latest();

        if ($request->filled('faculty_id')) {
            $query->where('faculty_id', $request->faculty_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $adminFaculties = $query->paginate(10)->withQueryString();

        $faculties = Faculty::where('status', 'active')->orderBy('name')->get();

        $admins = User::whereIn('role', ['admin', 'superadmin'])
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.admin-faculties.index', compact(
            'adminFaculties',  // <-- INI YANG DIGUNAKAN DI VIEW
            'faculties',
            'admins'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'faculty_id' => ['required', 'exists:faculties,id'],
            'position' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $user = User::findOrFail($validated['user_id']);

        // ✅ Cek apakah user memiliki role yang sesuai
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            return back()->withInput()->with('error', 'User yang dipilih bukan admin atau superadmin.');
        }

        // ✅ Cek apakah user sudah menjadi admin fakultas di fakultas yang sama
        $existing = AdminFaculty::where('user_id', $validated['user_id'])
            ->where('faculty_id', $validated['faculty_id'])
            ->exists();

        if ($existing) {
            return back()->withInput()->with('error', 'Admin tersebut sudah terdaftar pada fakultas yang dipilih.');
        }

        AdminFaculty::create($validated);

        return redirect()->route('admin.admin-faculties.index')
            ->with('success', 'Admin fakultas berhasil ditambahkan.');
    }

    // ✅ TAMBAHKAN METHOD EDIT & UPDATE
    public function edit(AdminFaculty $adminFaculty)
    {
        $faculties = Faculty::where('status', 'active')->orderBy('name')->get();
        $users = User::whereIn('role', ['admin', 'superadmin'])
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.admin-faculties.edit', compact('adminFaculty', 'faculties', 'users'));
    }

    public function update(Request $request, AdminFaculty $adminFaculty)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'faculty_id' => ['required', 'exists:faculties,id'],
            'position' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $user = User::findOrFail($validated['user_id']);

        if (!in_array($user->role, ['admin', 'superadmin'])) {
            return back()->withInput()->with('error', 'User yang dipilih bukan admin atau superadmin.');
        }

        // Cek duplikasi kecuali untuk record ini sendiri
        $exists = AdminFaculty::where('user_id', $validated['user_id'])
            ->where('faculty_id', $validated['faculty_id'])
            ->where('id', '!=', $adminFaculty->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Admin tersebut sudah terdaftar pada fakultas yang dipilih.');
        }

        $adminFaculty->update($validated);

        return redirect()->route('admin.admin-faculties.index')
            ->with('success', 'Admin fakultas berhasil diperbarui.');
    }

    public function destroy(AdminFaculty $adminFaculty)
    {
        $adminFaculty->delete();

        return redirect()
            ->route('admin.admin-faculties.index')
            ->with('success', 'Admin fakultas berhasil dihapus.');
    }
}
