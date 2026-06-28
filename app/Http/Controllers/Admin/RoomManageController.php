<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Faculty;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomManageController extends Controller
{
    /**
     * Display a listing of rooms.
     */
    public function index()
    {
        $rooms = Room::with(['faculty', 'facilities'])->get();

        $totalRooms = Room::count();
        $totalTersedia = Room::where('status', 'Tersedia')->count();
        $totalDipakai = Room::where('status', '!=', 'Tersedia')->count();
        $totalKapasitas = Room::sum('kapasitas');

        $faculties = Faculty::where('status', 'active')->get();

        return view('admin.rooms.index', compact(
            'rooms',
            'totalRooms',
            'totalTersedia',
            'totalDipakai',
            'totalKapasitas',
            'faculties'
        ));
    }

    /**
     * Show form for creating a new room.
     */
    public function create()
    {
        $faculties = Faculty::where('status', 'active')->get();
        $allFacilities = Facility::all();
        return view('admin.rooms.create', compact('faculties', 'allFacilities'));
    }

    /**
     * Store a newly created room.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'kode' => 'required|string|max:20|unique:rooms,kode',
            'gedung' => 'required|string|max:100',
            'lantai' => 'required|integer|min:1',
            'kapasitas' => 'required|integer|min:1',
            'alamat' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'max_durasi_jam' => 'required|integer|min:1|max:24',
            'status' => 'required|in:Tersedia,Maintenance',
            'faculty_id' => 'nullable|exists:faculties,id',
            'foto' => 'nullable|image|max:2048',
            'facilities' => 'array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        // Upload foto
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('rooms', 'public');
            $validated['foto'] = $path;
        }

        $room = Room::create($validated);

        // Attach facilities
        if ($request->filled('facilities')) {
            $facilities = [];
            foreach ($request->facilities as $facilityId) {
                $facilities[$facilityId] = ['status' => 'tersedia'];
            }
            $room->facilities()->attach($facilities);
        }

        return redirect()->route('admin.rooms.index')
            ->with('success', "Ruang \"{$room->nama}\" berhasil ditambahkan.");
    }

    /**
     * Display room details.
     */
    public function show(int $id)
    {
        $room = Room::with(['faculty', 'facilities', 'bookings' => function ($query) {
            $query->where('status', '!=', Booking::STATUS_CANCELLED)
                  ->orderBy('tanggal', 'desc')
                  ->limit(10);
        }])->findOrFail($id);

        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show form for editing room.
     */
    public function edit(int $id)
    {
        $room = Room::with(['facilities'])->findOrFail($id);
        $faculties = Faculty::where('status', 'active')->get();
        $allFacilities = Facility::all();

        return view('admin.rooms.edit', compact('room', 'faculties', 'allFacilities'));
    }

    /**
     * Update room.
     */
    public function update(Request $request, int $id)
    {
        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'kode' => 'required|string|max:20|unique:rooms,kode,' . $id,
            'gedung' => 'required|string|max:100',
            'lantai' => 'required|integer|min:1',
            'kapasitas' => 'required|integer|min:1',
            'alamat' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'max_durasi_jam' => 'required|integer|min:1|max:24',
            'status' => 'required|in:Tersedia,Maintenance',
            'faculty_id' => 'nullable|exists:faculties,id',
            'foto' => 'nullable|image|max:2048',
            'facilities' => 'array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        // Upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($room->foto && Storage::disk('public')->exists($room->foto)) {
                Storage::disk('public')->delete($room->foto);
            }
            $path = $request->file('foto')->store('rooms', 'public');
            $validated['foto'] = $path;
        }

        $room->update($validated);

        // Sync facilities
        if ($request->filled('facilities')) {
            $facilities = [];
            foreach ($request->facilities as $facilityId) {
                $facilities[$facilityId] = ['status' => 'tersedia'];
            }
            $room->facilities()->sync($facilities);
        } else {
            $room->facilities()->detach();
        }

        return redirect()->route('admin.rooms.index')
            ->with('success', "Ruang \"{$room->nama}\" berhasil diperbarui.");
    }

    /**
     * Toggle room status.
     */
    public function toggleStatus(int $id)
    {
        $room = Room::findOrFail($id);
        $room->status = $room->status === 'Tersedia' ? 'Maintenance' : 'Tersedia';
        $room->save();

        $statusLabel = $room->getStatusLabelAttribute();

        return redirect()->route('admin.rooms.index')
            ->with('success', "Status ruang \"{$room->nama}\" berhasil diubah menjadi {$statusLabel}.");
    }

    /**
     * Delete room.
     */
    public function destroy(int $id)
    {
        $room = Room::findOrFail($id);

        // Hapus foto
        if ($room->foto && Storage::disk('public')->exists($room->foto)) {
            Storage::disk('public')->delete($room->foto);
        }

        // Detach facilities
        $room->facilities()->detach();

        $roomName = $room->nama;
        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', "Ruang \"{$roomName}\" berhasil dihapus.");
    }

    /**
     * Reset rooms (untuk development/testing).
     */
    public function reset()
    {
        // Hapus semua data room
        Room::truncate();

        // Buat data default
        $defaultRooms = $this->defaultRooms();

        foreach ($defaultRooms as $data) {
            $room = Room::create([
                'nama' => $data['nama'],
                'kode' => $data['kode'],
                'gedung' => $data['gedung'],
                'lantai' => $data['lantai'],
                'kapasitas' => $data['kapasitas'],
                'alamat' => $data['alamat'],
                'deskripsi' => $data['deskripsi'],
                'jam_buka' => $data['jam_buka'],
                'jam_tutup' => $data['jam_tutup'],
                'max_durasi_jam' => (int) filter_var($data['max_durasi'], FILTER_SANITIZE_NUMBER_INT),
                'status' => $data['status'],
                'foto' => $data['foto'],
            ]);
        }

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Data ruang berhasil direset ke default.');
    }

    private function defaultRooms(): array
    {
        return [
            [
                'nama' => 'Ruang Seminar A - Lt. 3',
                'kode' => 'RSA-301',
                'gedung' => 'Gedung A',
                'lantai' => 3,
                'kapasitas' => 120,
                'alamat' => 'Kampus Tengah Undiksha, Gedung A, Lantai 3',
                'deskripsi' => 'Ruang seminar luas dengan panggung dan sistem audio premium.',
                'jam_buka' => '07:00',
                'jam_tutup' => '21:00',
                'max_durasi' => '8 jam/hari',
                'status' => 'Tersedia',
                'foto' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&q=80',
            ],
            [
                'nama' => 'Ruang Rapat 205',
                'kode' => 'RR-205',
                'gedung' => 'Gedung B',
                'lantai' => 2,
                'kapasitas' => 25,
                'alamat' => 'Kampus Tengah Undiksha, Gedung B, Lantai 2',
                'deskripsi' => 'Ruang rapat eksekutif dengan meja oval dan TV layar besar.',
                'jam_buka' => '08:00',
                'jam_tutup' => '17:00',
                'max_durasi' => '4 jam/hari',
                'status' => 'Tersedia',
                'foto' => 'https://images.unsplash.com/photo-1604328698692-f76ea9498e76?w=600&q=80',
            ],
            [
                'nama' => 'Ruang Kuliah B-12',
                'kode' => 'RKB-12',
                'gedung' => 'Gedung B',
                'lantai' => 1,
                'kapasitas' => 60,
                'alamat' => 'Kampus Tengah Undiksha, Gedung B, Lantai 1',
                'deskripsi' => 'Ruang kuliah standar dengan kursi ergonomis dan proyektor.',
                'jam_buka' => '07:00',
                'jam_tutup' => '20:00',
                'max_durasi' => '6 jam/hari',
                'status' => 'Tersedia',
                'foto' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&q=80',
            ],
            [
                'nama' => 'Lab Komputer C-03',
                'kode' => 'LC-303',
                'gedung' => 'Gedung C',
                'lantai' => 3,
                'kapasitas' => 40,
                'alamat' => 'Kampus Tengah Undiksha, Gedung C, Lantai 3',
                'deskripsi' => 'Laboratorium komputer lengkap dengan 40 unit PC.',
                'jam_buka' => '07:00',
                'jam_tutup' => '20:00',
                'max_durasi' => '6 jam/hari',
                'status' => 'Tersedia',
                'foto' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=600&q=80',
            ],
            [
                'nama' => 'Aula Utama Kampus',
                'kode' => 'AUK-101',
                'gedung' => 'Gedung Pusat',
                'lantai' => 1,
                'kapasitas' => 500,
                'alamat' => 'Kampus Tengah Undiksha, Gedung Pusat, Lantai 1',
                'deskripsi' => 'Aula terbesar kampus dengan kapasitas 500 orang.',
                'jam_buka' => '07:00',
                'jam_tutup' => '22:00',
                'max_durasi' => '10 jam/hari',
                'status' => 'Tersedia',
                'foto' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&q=80',
            ],
            [
                'nama' => 'Meeting Room Lt. 4',
                'kode' => 'MR-401',
                'gedung' => 'Gedung A',
                'lantai' => 4,
                'kapasitas' => 12,
                'alamat' => 'Kampus Tengah Undiksha, Gedung A, Lantai 4',
                'deskripsi' => 'Ruang meeting kecil dan nyaman dengan sofa.',
                'jam_buka' => '08:00',
                'jam_tutup' => '17:00',
                'max_durasi' => '4 jam/hari',
                'status' => 'Tersedia',
                'foto' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=600&q=80',
            ],
        ];
    }
}
