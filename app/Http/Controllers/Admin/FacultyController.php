<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FacultyController extends Controller
{
    public function index(Request $request)
    {
        $query = Faculty::withCount('users')->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $faculties = $query->paginate(10)->withQueryString();

        // ===== KIRIMKAN SEMUA VARIABEL YANG DIBUTUHKAN =====
        $admins = User::whereIn('role', ['admin', 'superadmin'])
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Untuk admin-faculties (jika view membutuhkan)
        $adminFaculties = collect(); // empty collection

        return view('admin.faculties.index', compact('faculties', 'admins', 'adminFaculties'));
    }

    public function create()
    {
        return view('admin.faculties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:20', 'unique:faculties,code'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        Faculty::create($validated);

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function show(Faculty $faculty)
    {
        $faculty->load('users');
        return view('admin.faculties.show', compact('faculty'));
    }

    public function edit(Faculty $faculty)
    {
        return view('admin.faculties.edit', compact('faculty'));
    }

    public function update(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('faculties', 'code')->ignore($faculty->id),
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $faculty->update($validated);

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', 'Data fakultas berhasil diperbarui.');
    }

    public function destroy(Faculty $faculty)
    {
        if ($faculty->users()->count() > 0) {
            return back()->with('error', 'Fakultas tidak dapat dihapus karena masih memiliki user.');
        }

        $faculty->delete();

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', 'Fakultas berhasil dihapus.');
    }
}
