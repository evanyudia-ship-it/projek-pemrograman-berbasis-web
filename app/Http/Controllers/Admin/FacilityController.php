<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::withCount(['rooms'])->get();

        $totalFacilities = Facility::count();
        $totalRoomsWithFacilities = Facility::has('rooms')->count();

        return view('admin.facilities.index', compact(
            'facilities',
            'totalFacilities',
            'totalRoomsWithFacilities'
        ));
    }

    public function create()
    {
        return view('admin.facilities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:facilities,nama',
            'icon' => 'nullable|string|max:10',
            'kategori' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        $facility = Facility::create($validated);

        return redirect()->route('admin.facilities.index')
            ->with('success', "Fasilitas \"{$facility->nama}\" berhasil ditambahkan.");
    }

    public function show(int $id)
    {
        $facility = Facility::with(['rooms' => function ($query) {
            $query->withPivot('status');
        }])->findOrFail($id);

        $rooms = $facility->rooms;

        return view('admin.facilities.show', compact('facility', 'rooms'));
    }

    public function edit(int $id)
    {
        $facility = Facility::findOrFail($id);
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request, int $id)
    {
        $facility = Facility::findOrFail($id);

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('facilities', 'nama')->ignore($facility->id),
            ],
            'icon' => 'nullable|string|max:10',
            'kategori' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        $facility->update($validated);

        return redirect()->route('admin.facilities.index')
            ->with('success', "Fasilitas \"{$facility->nama}\" berhasil diperbarui.");
    }

    public function destroy(int $id)
    {
        $facility = Facility::findOrFail($id);

        // Cek apakah fasilitas masih digunakan di ruangan
        if ($facility->rooms()->count() > 0) {
            return back()->with('error', 'Fasilitas "' . $facility->nama . '" masih digunakan oleh ' . $facility->rooms()->count() . ' ruangan. Hapus relasi terlebih dahulu.');
        }

        $facilityName = $facility->nama;
        $facility->delete();

        return redirect()->route('admin.facilities.index')
            ->with('success', "Fasilitas \"{$facilityName}\" berhasil dihapus.");
    }

    public function getRooms(int $id)
    {
        $facility = Facility::with(['rooms' => function ($query) {
            $query->withPivot('status')->select('rooms.id', 'rooms.nama', 'rooms.kode');
        }])->findOrFail($id);

        return response()->json([
            'facility' => $facility->nama,
            'rooms' => $facility->rooms->map(function ($room) {
                return [
                    'id' => $room->id,
                    'nama' => $room->nama,
                    'kode' => $room->kode,
                    'status' => $room->pivot->status,
                    'status_label' => $room->pivot->getStatusLabelAttribute(),
                ];
            }),
        ]);
    }
}
